<?php

namespace App\Console\Commands;

use App\Models\Shops as ShopsModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
            }
        }
    }

    public function a(ShopsModel $shop)
    {
        // 获取店铺下广告账号ID
        $response = Http::withHeaders([
            'Access-Token' => $shop->access_token,
        ])->withBody(json_encode([
            'shop_id' => $shop->advertiser_id,
            'page_size' => 100
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/shop/advertiser/list/');

        if ($response->json('code') != 0) {
            return error('获取店铺关联的广告账号信息失败! 原因：' . $response->json('message'));
        }

        $adverIds = $response->json('data.list');

    }
}
