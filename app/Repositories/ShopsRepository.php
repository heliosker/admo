<?php

namespace App\Repositories;

use App\Models\Shops;
use App\Repositories\BaseRepository;

/**
 * Class shopsRepository
 * @package App\Repositories
 * @version May 21, 2023, 3:54 pm UTC
 */
class ShopsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'advertiser_id',
        'advertiser_name',
        'is_valid',
        'account_role',
        'created_at',
        'updated_at',
        'has_child'
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

    public function search($advertiserId, $name, $isValid, $parentId = 0, $paginate = true, $perPage = 15)
    {
        $query = $this->model->query();
        if ($advertiserId !== null) {
            $query->where('advertiser_id', $advertiserId);
        }

        if ($name !== null) {
            $query->where('advertiser_name', 'like', "%$name%");
        }

        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        }

        if ($isValid !== null) {
            $query->where('is_valid', $isValid);
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
        return Shops::class;
    }
}
