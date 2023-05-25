<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Response;
use App\Models\shops;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Repositories\ShopsRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\UpdateshopsAPIRequest;
use App\Http\Requests\API\CreateShopsAPIRequest;

/**
 * Class shopsController
 * @package App\Http\Controllers\API
 */
class ShopsAPIController extends AppBaseController
{
    /** @var  ShopsRepository */
    private $shopsRepository;

    public function __construct(ShopsRepository $shopsRepo)
    {
        $this->shopsRepository = $shopsRepo;
    }

    /**
     * Display a listing of the shops.
     * GET|HEAD /shops
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $advertiserId = $request->get('advertiser_id');
        $name = $request->get('name');
        $isValid = $request->get('is_valid');
        $parentId = $request->get('parent_id');

        $shops = $this->shopsRepository->search($advertiserId, $name, $isValid, $parentId);

        return result($shops, 'Shops retrieved successfully');
    }

    /**
     * Tree
     *
     *  title: 'Node1',
     * value: '0-0',
     * key: '0-0',
     * disabled: true,
     * children: [
     * @param Request $request
     * @return JsonResponse
     */
    public function trees(Request $request)
    {
        $shops = $this->shopsRepository->children();
        if (!empty($shops)) {
            foreach ($shops as $key => $shop) {
                $shops[$key]['children'] = $this->shopsRepository->children($shop['id']);
            }
        }
        return result($shops);
    }

    /**
     * Store a newly created shops in storage.
     * POST /shops
     *
     * @param CreateShopsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateShopsAPIRequest $request)
    {
        $input = $request->all();

        $shops = $this->shopsRepository->create($input);

        return $this->sendResponse($shops->toArray(), 'Shops saved successfully');
    }

    /**
     * Display the specified shops.
     * GET|HEAD /shops/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var shops $shops */
        $shops = $this->shopsRepository->find($id);

        if (empty($shops)) {
            return $this->sendError('Shops not found');
        }

        return $this->sendResponse($shops->toArray(), 'Shops retrieved successfully');
    }

    /**
     * Update the specified shops in storage.
     * PUT/PATCH /shops/{id}
     *
     * @param int $id
     * @param UpdateshopsAPIRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, UpdateShopsAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var shops $shops */
        $shops = $this->shopsRepository->find($id);

        if (empty($shops)) {
            return error('Shops not found', 404);
        }

        $shops = $this->shopsRepository->update($input, $id);

        return result($shops, 'shops updated successfully');
    }

    /**
     * Remove the specified shops from storage.
     * DELETE /shops/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var shops $shops */
        $shops = $this->shopsRepository->find($id);

        if (empty($shops)) {
            return $this->sendError('Shops not found');
        }

        $shops->delete();

        return $this->sendSuccess('Shops deleted successfully');
    }

    /**
     * Get a authorization url
     *
     * Date: 2021/6/5
     * @return JsonResponse
     */
    public function authorization(): JsonResponse
    {
        $domain = 'https://qianchuan.jinritemai.com/openapi/qc/audit/oauth.html';
        $query = http_build_query([
            'app_id' => config('ocean.appid'),
            'state' => '',
            'material_auth' => 1,
            'redirect_uri' => route('auth.callback'),
            'rid' => 'ap04083hes'
        ]);
        return success([
            'url' => sprintf('%s?%s', $domain, $query)
        ]);
    }

    /**
     * 获取授权店铺
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse
    {
        $auth_code = $request->get('auth_code');

        $tokenRes = Http::post('https://ad.oceanengine.com/open_api/oauth2/access_token/', [
            'appid' => config('ocean.appid'),
            'secret' => config('ocean.secret'),
            'grant_type' => 'auth_code',
            'auth_code' => $auth_code
        ]);

        if ($tokenRes->status() != 200) {
            return error('获取店铺令牌失败');
        }

        $storeRes = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('https://ad.oceanengine.com/open_api/oauth2/advertiser/get/', [
            'access_token' => $tokenRes->json('data.access_token'),
            'app_id' => config('ocean.appid'),
            'secret' => config('ocean.secret')
        ]);

        if ($storeRes->json('code') != 0) {
            return error('获取店铺信息失败! 原因：' . $storeRes->json('message'));
        }

        $stores = $storeRes->json('data.list');

        if (is_array($stores) == false) {
            return error('获取店铺信息失败! 原因：没有授权任何店铺！');
        }
        $createNum = 0;
        $updateNum = 0;
        foreach ($stores as $store) {
            /**
             * @var Shops $exists
             */
            $exists = Shops::where('advertiser_id', $store['advertiser_id'])->first();
            $attributes = [
                'advertiser_id' => $store['advertiser_id'],
                'advertiser_name' => $store['advertiser_name'],
                'account_role' => $store['account_role'],
                'is_valid' => $store['is_valid'],
                'access_token' => $tokenRes->json('data.access_token'),
                'refresh_token' => $tokenRes->json('data.refresh_token'),
                'access_token_expires_at' => time() + $tokenRes->json('data.expires_in') - 60,
                'refresh_token_expires_at' => time() + $tokenRes->json('data.refresh_token_expires_in') - 60,
            ];
            if ($exists) {
                $exists->update($attributes);
                $updateNum += 1;
            } else {
                Shops::create($attributes);
                $createNum += 1;
            }
        }
        return result(['created_num' => $createNum, 'updated_num' => $updateNum], '授权店铺/代理商成功!新增:[' . $createNum . ']个！更新:[' . $updateNum . ']个！');
    }

    /**
     * 获取店铺下的广告账户ID
     *
     * Date: 2021/6/7
     * @param Shops $store
     * @return JsonResponse
     * @throws Exception
     */
    public function syncAdvertisers(Shops $store): JsonResponse
    {
        // 获取店铺下广告账号ID
        $response = Http::withHeaders([
            'Access-Token' => $store->access_token,
        ])->withBody(json_encode([
            'shop_id' => $store->advertiser_id,
            'page_size' => 100
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/shop/advertiser/list/');

        if ($response->json('code') != 0) {
            return error('获取店铺关联的广告账号信息失败! 原因：' . $response->json('message'));
        }

        $adverIds = $response->json('data.list');

        // 获取广告账号信息
        $rsp = Http::withHeaders([
            'Access-Token' => $store->access_token
        ])->withBody(json_encode([
            'advertiser_ids' => $adverIds
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/2/advertiser/public_info/', [
        ]);

        if ($rsp->json('code') != 0) {
            return error('获取广告账号详细信息失败! 原因：' . $rsp->json('message'));
        }

        $advertisers = $rsp->json('data');

        $result = [
            'exists' => 0,
            'append' => 0
        ];
        foreach ($advertisers as $advertiser) {
            $account = Shops::where('advertiser_id', $advertiser['id'])
                ->where('parent_id', $store->id)
                ->first();
            if ($account) {
                $result['exists']++;
            } else {
                Shops::create([
                    'advertiser_id' => $advertiser['id'],
                    'advertiser_name' => $advertiser['name'],
                    'company' => $advertiser['company'],
                    'first_name' => $advertiser['first_industry_name'],
                    'second_name' => $advertiser['second_industry_name'],
                    'parent_id' => $store->id
                ]);
                $result['append']++;
            }
        }

        return result($result, '同步子账号成功!');
    }
}
