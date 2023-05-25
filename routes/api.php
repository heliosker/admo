<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdsAPIController;
use App\Http\Controllers\API\ShopsAPIController;
use App\Http\Controllers\API\AdminUserAPIController;
use App\Http\Controllers\API\AlarmLogsAPIController;
use App\Http\Controllers\API\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('sign', [AuthenticationController::class, 'sign'])->middleware('guest');
    Route::post('out', [AuthenticationController::class, 'out'])->middleware('auth:api');
});

Route::get('auth/callback', [ShopsAPIController::class, 'callback'])->name('auth.callback');

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('admins', [AdminUserAPIController::class, 'index']);

    Route::resource('tasks', TaskAPIController::class);

    // 列表
    Route::get('shops', [ShopsAPIController::class, 'index']);

    // 店铺设置
    Route::put('shops/{id}', [ShopsAPIController::class, 'update']);

    Route::get('shops/trees', [ShopsAPIController::class, 'trees']);

    // 同步子账号
    Route::post('shop/{store}/sync/advertisers', [ShopsAPIController::class, 'syncAdvertisers']);

    // 同步子账号下广告计划
    Route::post('advertiser/{store}/sync/ad', [AdsAPIController::class, 'syncAd']);

    Route::get('ads', [AdsAPIController::class, 'index']);

    Route::get('alarm_logs', [AlarmLogsAPIController::class, 'index']);
    
});


Route::resource('tags', App\Http\Controllers\API\TagsAPIController::class);
