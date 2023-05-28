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
        $shopsTokenGroup = ShopsModel::select('refresh_token')->mainAccount()->distinct()->get();

        if ($shopsTokenGroup->isNotEmpty()) {

            foreach ($shopsTokenGroup as $shopsToken) {

                $data = $this->refreshToken($shopsToken->refresh_token);

                // 根据 Refresh Token 刷新
                if (!empty($data)) {
                    $upFields = [];
                    $upFields['access_token'] = $data['access_token'];
                    $upFields['access_token_expires_at'] = time() + $data['expires_in'];
                    $upFields['refresh_token'] = $data['refresh_token'];
                    $upFields['refresh_token_expires_at'] = time() + $data['refresh_token_expires_in'];

                    $upCnt = ShopsModel::where('refresh_token', $shopsToken->refresh_token)->update($upFields);
                    if ($upCnt > 0) {
                        $this->info('Refresh Token Success. old refresh Token:' . $shopsToken->refresh_token . ' New Refresh Token:' . $upFields['refresh_token']);
                    } else {
                        Log::error('Refresh Token Error. old refresh Token:' . $shopsToken->refresh_token);
                    }
                } else {
                    $this->info('Refresh Token Error. Refresh_token:' . $shopsToken->refresh_token);
                }
            }
        }
    }

    public function refreshToken($refreshToken)
    {
        $rsp = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://ad.oceanengine.com/open_api/oauth2/refresh_token/', [
            'refresh_token' => $refreshToken,
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
