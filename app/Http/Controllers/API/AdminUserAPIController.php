<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAdminUserAPIRequest;
use App\Http\Requests\API\UpdateAdminUserAPIRequest;
use App\Models\AdminUser;
use App\Repositories\AdminUserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class AdminUserController
 * @package App\Http\Controllers\API
 */

class AdminUserAPIController extends AppBaseController
{
    /** @var  AdminUserRepository */
    private $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepo)
    {
        $this->adminUserRepository = $adminUserRepo;
    }

    /**
     * Display a listing of the AdminUser.
     * GET|HEAD /adminUsers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $adminUsers = $this->adminUserRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($adminUsers->toArray(), 'Admin Users retrieved successfully');
    }

    /**
     * Store a newly created AdminUser in storage.
     * POST /adminUsers
     *
     * @param CreateAdminUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAdminUserAPIRequest $request)
    {
        $input = $request->all();

        $adminUser = $this->adminUserRepository->create($input);

        return $this->sendResponse($adminUser->toArray(), 'Admin User saved successfully');
    }

    /**
     * Display the specified AdminUser.
     * GET|HEAD /adminUsers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AdminUser $adminUser */
        $adminUser = $this->adminUserRepository->find($id);

        if (empty($adminUser)) {
            return $this->sendError('Admin User not found');
        }

        return $this->sendResponse($adminUser->toArray(), 'Admin User retrieved successfully');
    }

    /**
     * Update the specified AdminUser in storage.
     * PUT/PATCH /adminUsers/{id}
     *
     * @param int $id
     * @param UpdateAdminUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdminUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var AdminUser $adminUser */
        $adminUser = $this->adminUserRepository->find($id);

        if (empty($adminUser)) {
            return $this->sendError('Admin User not found');
        }

        $adminUser = $this->adminUserRepository->update($input, $id);

        return $this->sendResponse($adminUser->toArray(), 'AdminUser updated successfully');
    }

    /**
     * Remove the specified AdminUser from storage.
     * DELETE /adminUsers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AdminUser $adminUser */
        $adminUser = $this->adminUserRepository->find($id);

        if (empty($adminUser)) {
            return $this->sendError('Admin User not found');
        }

        $adminUser->delete();

        return $this->sendSuccess('Admin User deleted successfully');
    }
}
