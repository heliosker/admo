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

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Shops::class;
    }
}
