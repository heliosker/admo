<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAdsAPIRequest;
use App\Http\Requests\API\UpdateAdsAPIRequest;
use App\Models\Ads;
use App\Repositories\AdsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

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
     * @return Response
     */
    public function index(Request $request)
    {
        $ads = $this->adsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($ads->toArray(), 'Ads retrieved successfully');
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
     * @throws \Exception
     *
     * @return Response
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
