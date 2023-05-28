<?php

namespace App\Console\Commands;

use App\Models\Ads;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SyncAdsDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ads:detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '补全广告详情信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 补全创意字段，智能放量：auto_extend_enabled
        $ads = Ads::where('status', Ads::STATUS_DELIVERY_OK)->get();
        if ($ads->isNotEmpty()) {
            foreach ($ads as $ad) {
                $token = $ad->advertiser->getShopAccessToken();
                $autoEnabled = $this->syncAdsDetail($ad, $token);
                if ($autoEnabled !== null) {
                    $ad->auto_extend_enabled = $autoEnabled;
                    if ($ad->save()) {
                        $this->line('同步计划智能放量字段成功；ID：' . $ad->ad_id);
                    }
                }
            }
        }
        return 0;
    }

    public function syncAdsDetail(Ads $ad, $token)
    {
        $rsp = Http::withHeaders(['Content-Type' => 'application/json', 'Access-Token' => $token])
            ->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/ad/detail/get/', ['advertiser_id' => $ad->adv_id, 'ad_id' => $ad->ad_id]);

        if ($rsp->json('code') != 0) {
            $this->line('同步计划详情失败：ID：' . $ad->ad_id . ' Message:' . $rsp->json('message'));
            Log::error('同步计划详情失败：ID：' . $ad->ad_id . ' Message:' . $rsp->json('message'));
        }

        $data = $rsp->json('data');
        return $data['audience']['auto_extend_enabled'] ?? null;
    }
}
