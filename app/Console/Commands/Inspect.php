<?php

namespace App\Console\Commands;

use App\Models\AlarmLogs;
use App\Models\Shops as ShopsModel;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Inspect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspect:unbind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '巡查子账号是否解绑';

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
        $shops = ShopsModel::query()->mainAccount()->get();
        if ($shops->isNotEmpty()) {
            foreach ($shops as $shop) {
                // 不允许解绑时检测
                if ($shop->is_allow_unbind == ShopsModel::NOT_ALLOW_UNBIND) {
                    $advIdsSync = $this->adv($shop);

                    $subs = ShopsModel::where('parent_id', $shop->id)->get();
                    if ($subs->isNotEmpty()) {
                        foreach ($subs as $sub) {
                            if (in_array($sub->advertiser_id, $advIdsSync)) {
                                // 更新同步时间
                                $sub->scanned_at = Carbon::now();
                                $sub->save();
                                $this->info('更新同步时间, ID:' . $sub->advertiser_id . ' Name:' . $sub->advertiser_name);
                                Log::error('更新同步时间, ID:' . $sub->advertiser_id . ' Name:' . $sub->advertiser_name);
                            } else {
                                // 已解绑
                                $sub->is_valid = ShopsModel::INVALID;
                                $sub->save();
                                $this->info('解绑账号, ID:' . $sub->advertiser_id . ' Name:' . $sub->advertiser_name);
                                Log::error('解绑账号, ID:' . $sub->advertiser_id . ' Name:' . $sub->advertiser_name);

                                // 创建解绑日记
                                $log = (new AlarmLogs());
                                $log->adver_id = $sub->advertiser_id;
                                $log->ad_name = $sub->advertiser_name;
                                $log->is_valid = ShopsModel::INVALID;
                                $log->type = AlarmLogs::TYPE_UNBIND;
                                $log->save();
                            }
                        }
                    }
                }
                
                // 更新主巡查时间
                $shop->scanned_at = Carbon::now();
                $shop->save();
            }
        }
    }

    public function adv(ShopsModel $shop)
    {
        // 获取店铺下广告账号ID
        $response = Http::withHeaders([
            'Access-Token' => $shop->access_token,
        ])->withBody(json_encode([
            'shop_id' => $shop->advertiser_id,
            'page_size' => 100
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/shop/advertiser/list/');

        if ($response->json('code') != 0) {
            Log::error('获取店铺关联的广告账号信息失败! ID:' . $shop->advertiser_id . ' 原因：' . $response->json('message'));
            return [];
        }

        return $response->json('data.list');
    }
}
