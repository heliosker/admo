<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shops as ShopsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Refresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shops:refresh:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '主账号 AccessToken 更新';

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
     */
    public function handle()
    {
        $shops = ShopsModel::query()->mainAccount()->get();

        if ($shops->isNotEmpty()) {
            foreach ($shops as $shop) {
                dump($shop->advertiser_id, $shop->advertiser_name);
                $data = $this->refreshToken($shop);
                dump($data);
//                continue;
                DB::enableQueryLog();
                // 根据 Refresh Token 刷新
                if (!empty($data)) {
                    $shop->access_token = $data['access_token'];
                    $shop->access_token_expires_at = time() + $data['expires_in'];
                    $shop->refresh_token = $data['refresh_token'];
                    $shop->refresh_token_expires_at = time() + $data['refresh_token_expires_in'];
                    if ($shop->save()) {
                        $this->info('Refresh Token Success. ID:' . $shop->advertiser_id . ' Name:' . $shop->advertiser_name);
                    } else {
                        $this->info('Refresh Token Error. ID:' . $shop->advertiser_id . ' Name:' . $shop->advertiser_name);
                    }
                    dump(DB::getQueryLog());
                } else {
                    Log::error('shop', [$shop->advertiser_id, $shop->advertiser_name]);
                    $this->info('Refresh Token Error. ID:' . $shop->advertiser_id . ' Name:' . $shop->advertiser_name);
                }
            }
        }
    }

    public function refreshToken(ShopsModel $shop)
    {
        $rsp = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://ad.oceanengine.com/open_api/oauth2/refresh_token/', [
            'refresh_token' => $shop->refresh_token,
            'app_id' => config('ocean.appid'),
            'secret' => config('ocean.secret'),
            'grant_type' => 'refresh_token'
        ]);

        if ($rsp->json('code') != 0) {
            Log::error('refresh_token Error! Message：' . $rsp->json('message'));
            return [];
        }
        return $rsp->json('data');
    }
}
