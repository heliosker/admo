<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAlarmLogsAPIRequest;
use App\Http\Requests\API\UpdateAlarmLogsAPIRequest;
use App\Models\AlarmLogs;
use App\Repositories\AlarmLogsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class AlarmLogsController
 * @package App\Http\Controllers\API
 */
class AlarmLogsAPIController extends AppBaseController
{
    /** @var  AlarmLogsRepository */
    private $alarmLogsRepository;

    public function __construct(AlarmLogsRepository $alarmLogsRepo)
    {
        $this->alarmLogsRepository = $alarmLogsRepo;
    }

    /**
     * Display a listing of the AlarmLogs.
     * GET|HEAD /alarmLogs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $alarmLogs = $this->alarmLogsRepository->search(
            $request->input('type'),
            $request->input('task_name'),
            $request->input('adver_id'),
            $request->input('punish_rule'),
            $request->input('start_at'),
            $request->input('end_at'),
            true,
            $request->input('limit'),
        );

        return result($alarmLogs, 'Alarm Logs retrieved successfully');
    }

    /**
     * Store a newly created AlarmLogs in storage.
     * POST /alarmLogs
     *
     * @param CreateAlarmLogsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAlarmLogsAPIRequest $request)
    {
        $input = $request->all();

        $alarmLogs = $this->alarmLogsRepository->create($input);

        return $this->sendResponse($alarmLogs->toArray(), 'Alarm Logs saved successfully');
    }

    /**
     * Display the specified AlarmLogs.
     * GET|HEAD /alarmLogs/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AlarmLogs $alarmLogs */
        $alarmLogs = $this->alarmLogsRepository->find($id);

        if (empty($alarmLogs)) {
            return $this->sendError('Alarm Logs not found');
        }

        return $this->sendResponse($alarmLogs->toArray(), 'Alarm Logs retrieved successfully');
    }

    /**
     * Update the specified AlarmLogs in storage.
     * PUT/PATCH /alarmLogs/{id}
     *
     * @param int $id
     * @param UpdateAlarmLogsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAlarmLogsAPIRequest $request)
    {
        $input = $request->all();

        /** @var AlarmLogs $alarmLogs */
        $alarmLogs = $this->alarmLogsRepository->find($id);

        if (empty($alarmLogs)) {
            return $this->sendError('Alarm Logs not found');
        }

        $alarmLogs = $this->alarmLogsRepository->update($input, $id);

        return $this->sendResponse($alarmLogs->toArray(), 'AlarmLogs updated successfully');
    }

    /**
     * Remove the specified AlarmLogs from storage.
     * DELETE /alarmLogs/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function destroy($id): JsonResponse
    {
        /** @var AlarmLogs $alarmLogs */
        $alarmLogs = $this->alarmLogsRepository->find($id);

        if (empty($alarmLogs)) {
            return error('Alarm Logs not found',404);
        }

        $alarmLogs->forceDelete();

        return result([],'Alarm Logs deleted successfully');
    }
}
