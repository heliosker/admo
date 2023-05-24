<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\ShopsAPIController;

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

    Route::resource('admins', AdminUserAPIController::class);

    Route::resource('tasks', TaskAPIController::class);

    Route::get('shops', [ShopsAPIController::class, 'index']);

    Route::get('shops/trees', [ShopsAPIController::class, 'trees']);

    Route::post('shop/{store}/sync/advertisers', [ShopsAPIController::class, 'syncAdvertisers']);

});


