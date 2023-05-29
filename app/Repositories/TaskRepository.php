<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Task;
use App\Repositories\BaseRepository;

/**
 * Class TaskRepository
 * @package App\Repositories
 * @version May 20, 2023, 3:16 pm UTC
 */
class TaskRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'adv_id',
        'peak_price',
        'is_allow_bulk',
        'is_allow_unbind',
        'punish',
        'status'
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

    public function search($name, $advId, $status, $startAt, $endAt, $paginate, $perPage)
    {
        $query = $this->model->query();
        if ($name !== null) {
            $query->where('name', 'like', "%$name%");
        }
        if ($advId !== null) {
            $query->where('adv_id', 'like', "%$advId%");
        }
        if ($status !== null) {
            $query->where('status', $status);
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
        return Task::class;
    }
}
