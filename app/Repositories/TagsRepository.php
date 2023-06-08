<?php

namespace App\Repositories;

use App\Models\Tags;
use App\Repositories\BaseRepository;

/**
 * Class TagsRepository
 * @package App\Repositories
 * @version May 25, 2023, 9:38 am UTC
 */
class TagsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'created_at',
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

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Tags::class;
    }

    public function search($name, $paginate, $perPage)
    {
        $query = $this->model->query();
        
        if ($name !== null) {
            $query->where('name', 'like', "%$name%");
        }

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
