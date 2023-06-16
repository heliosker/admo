<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $advId;
    protected $advIds;
    protected $accessToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $advId, array $advIds, string $accessToken)
    {
        $this->$advId = $advId;
        $this->advIds = $advIds;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $fields = [
            'ad_id',
            'stat_cost',// 消耗
            'pay_order_count',// 直接成交订单数
            'pay_order_amount',// 直接成交金额
            'prepay_and_pay_order_roi',// 直接支付roi
            'convert_cost',//转化成本
        ];
        $rsp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Token' => $this->accessToken
        ])->withBody(json_encode([
            'advertiser_id' => $this->advId,
            'start_date' => $this->advId,
            'end_date' => $this->advId,
            'fields' => $fields,
            'filtering' => [
                'marketing_goal' => 'LIVE_PROM_GOODS',
                'status' => 'TIME_DONE'
            ],
            'page_size' => 200
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/report/ad/get/', [
        ]);

        if ($rsp->json('code') != 0) {
            Log::error('获取计划失败！ID:' . $store->advertiser_id . $rsp->json('message'));
            $this->error('获取计划失败！ID:' . $store->advertiser_id . $rsp->json('message'));
            return false;
        }
        $data = $rsp->json('data');


    }
}
