<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Ads;
use App\Jobs\AdsJob;
use App\Models\Shops;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\AdsRepository;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\API\CreateAdsAPIRequest;
use App\Http\Requests\API\UpdateAdsAPIRequest;
use App\Http\Controllers\AppBaseController;

/**
 * Class AdsController
 * @package App\Http\Controllers\API
 */
class AdsAPIController extends AppBaseController
{
    /** @var  AdsRepository */
    private $adsRepository;

    public function __construct(AdsRepository $adsRepo)
    {
        $this->adsRepository = $adsRepo;
    }

    /**
     * Display a listing of the Ads.
     * GET|HEAD /ads
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $ads = $this->adsRepository->search(
            $request->input('adv_id'),
            $request->input('ad_id'),
            $request->input('ad_name'),
            $request->input('status'),
            true,
            $request->input('limit'),
        );

        return result($ads, 'Ads retrieved successfully');
    }

    public function syncAd(Request $request, Shops $store)
    {
        if ($store->parent_id == 0) {
            return error('主账号不能同步计划', 422);
        }

        $rsp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Token' => $store->getShopAccessToken()
        ])->withBody(json_encode([
            'advertiser_id' => $store->advertiser_id,
            'filtering' => [
                'marketing_goal' => 'LIVE_PROM_GOODS'
            ],
            'page_size' => 200
        ]), 'application/json')->get('https://ad.oceanengine.com/open_api/v1.0/qianchuan/ad/get/', [
        ]);

        if ($rsp->json('code') != 0) {
            return error('获取计划失败！' . $rsp->json('message'));
        }
        $data = $rsp->json('data');

        $totalNum = $data['page_info']['total_number'];
        $failNum = count($data['fail_list']);
        $createdNum = 0;
        $updatedNum = 0;

        if (count($data['list']) > 0) {
            foreach ($data['list'] as $item) {
                $input = $this->adsRepository->getInputFields($item);
                $input['adv_id'] = $store->advertiser_id;
                $exists = Ads::where('ad_id', $input['ad_id'])->first();
                if ($exists) {
                    $exists->update($input);
                    $updatedNum += 1;
                } else {
                    $ads = $this->adsRepository->create($input);
                    $createdNum += 1;
                }
            }
        }

        $result = [
            'total_num' => $totalNum,
            'fail_num' => $failNum,
            'created_num' => $createdNum,
            'updated_num' => $updatedNum
        ];
        return result($result, '同步计划成功！');
    }

    public function adReport($id)
    {
        /** @var Ads $ads */
        $ads = $this->adsRepository->find($id);

        $accessToken = $ads->advertiser->getShopAccessToken();
        $adsJob = new AdsJob([$ads->ad_ids], $accessToken);

        $adsJob->delay(Carbon::now()->addSeconds(30));
        dispatch($adsJob);

    }


    /**
     * Store a newly created Ads in storage.
     * POST /ads
     *
     * @param CreateAdsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAdsAPIRequest $request)
    {
        $input = $request->all();

        $ads = $this->adsRepository->create($input);

        return $this->sendResponse($ads->toArray(), 'Ads saved successfully');
    }

    /**
     * Display the specified Ads.
     * GET|HEAD /ads/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Ads $ads */
        $ads = $this->adsRepository->find($id);

        if (empty($ads)) {
            return $this->sendError('Ads not found');
        }

        return $this->sendResponse($ads->toArray(), 'Ads retrieved successfully');
    }

    /**
     * Update the specified Ads in storage.
     * PUT/PATCH /ads/{id}
     *
     * @param int $id
     * @param UpdateAdsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdsAPIRequest $request)
    {
        $input = $request->all();

        /** @var Ads $ads */
        $ads = $this->adsRepository->find($id);

        if (empty($ads)) {
            return $this->sendError('Ads not found');
        }

        $ads = $this->adsRepository->update($input, $id);

        return $this->sendResponse($ads->toArray(), 'Ads updated successfully');
    }

    /**
     * Remove the specified Ads from storage.
     * DELETE /ads/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Ads $ads */
        $ads = $this->adsRepository->find($id);

        if (empty($ads)) {
            return $this->sendError('Ads not found');
        }

        $ads->delete();

        return $this->sendSuccess('Ads deleted successfully');
    }
}
