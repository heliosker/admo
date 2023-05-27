<?php

namespace App\Console\Commands;

use App\Models\Ads;
use App\Models\Shops;
use App\Repositories\AdsRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAds extends Command
{
    /** @var  AdsRepository */
    private $adsRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时同步计划中广告';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AdsRepository $adsRepo)
    {
        $this->adsRepository = $adsRepo;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stores = Shops::subAccount()->get();
        if ($stores->isNotEmpty()) {
            foreach ($stores as $store) {
                $this->syncAds($store);
                $this->info('-------------');
            }
        }
    }

    public function syncAds(Shops $store)
    {
        $rsp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Token' => $store->getShopAccessToken()
        ])->withBody(json_encode([
            'advertiser_id' => $store->advertiser_id,
            'filtering' => [
                'marketing_goal' => 'LIVE_PROM_GOODS',
                'status' => 'TIME_DONE'
            ],
            'page_size' => 200
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/ad/get/', [
        ]);

        if ($rsp->json('code') != 0) {
            Log::error('获取计划失败！ID:' . $store->advertiser_id . $rsp->json('message'));
            $this->error('获取计划失败！ID:' . $store->advertiser_id . $rsp->json('message'));
            return false;
        }
        $data = $rsp->json('data');

        $totalNum = $data['page_info']['total_number'];
        $failNum = count($data['fail_list']);
        $createdNum = 0;
        $updatedNum = 0;

        if (count($data['list']) > 0) {
            foreach ($data['list'] as $item) {
                $input = $this->adsRepository->getInputFields($item);
                // 广告上关联广告主ID
                $input['adv_id'] = $store->advertiser_id;
                $exists = Ads::where('ad_id', $item['ad_id'])->first();
                if ($exists) {
                    $exists->update($input);
                    $updatedNum += 1;
                } else {
                    $ads = $this->adsRepository->create($input);
                    $createdNum += 1;
                }
            }
        }

        $this->info('ID:' . $store->advertiser_id . ' 总计再投计划：' . $totalNum, ' 获取失败：' . $failNum . ' 新增再投计划：' . $createdNum . ' 更新再投计划:' . $updatedNum);
        return true;
    }
}
