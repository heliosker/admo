<?php

namespace App\Repositories;

use App\Models\AlarmLogs;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class AlarmLogsRepository
 * @package App\Repositories
 * @version May 25, 2023, 9:24 am UTC
 */
class AlarmLogsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'task_id',
        'adv_id',
        'is_valid',
        'shop_id',
        'ad_name',
        'punish_rule',
        'exec_result',
        'created_at',
        'type',
        'updated_at'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function search($type, $taskName, $advId, $rule, $startAt, $endAt, $paginate = true, $perPage = 15)
    {

        $query = $this->model->query()->with('advertiser');

        if ($type !== null) {
            $query->where('type', $type);
        }
        if ($taskName !== null) {
            $query->where('task_name', 'like', "%$taskName%");
        }
        if ($advId !== null) {
            $query->where('adver_id', $advId);
        }
        if ($rule !== null) {
            $query->where('punish_rule', $rule);
        }
        if ($startAt !== null) {
            $query->where('created_at', '>=', Carbon::parse($startAt));
        }
        if ($endAt !== null) {
            $query->where('created_at', '<=', Carbon::parse($endAt));
        }

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AlarmLogs::class;
    }
}
