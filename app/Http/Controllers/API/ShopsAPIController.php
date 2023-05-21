<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\API\CreateshopsAPIRequest;
use App\Http\Requests\API\UpdateshopsAPIRequest;
use App\Models\shops;
use App\Repositories\shopsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class shopsController
 * @package App\Http\Controllers\API
 */

class ShopsAPIController extends AppBaseController
{
    /** @var  shopsRepository */
    private $shopsRepository;

    public function __construct(shopsRepository $shopsRepo)
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
    public function index(Request $request)
    {
        $shops = $this->shopsRepository->paginate(
            $request->get('limit')
        );

        return result($shops, 'Shops retrieved successfully');
    }

    /**
     * Store a newly created shops in storage.
     * POST /shops
     *
     * @param CreateshopsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateshopsAPIRequest $request)
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
     * @return Response
     */
    public function update($id, UpdateshopsAPIRequest $request)
    {
        $input = $request->all();

        /** @var shops $shops */
        $shops = $this->shopsRepository->find($id);

        if (empty($shops)) {
            return $this->sendError('Shops not found');
        }

        $shops = $this->shopsRepository->update($input, $id);

        return $this->sendResponse($shops->toArray(), 'shops updated successfully');
    }

    /**
     * Remove the specified shops from storage.
     * DELETE /shops/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
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
}
