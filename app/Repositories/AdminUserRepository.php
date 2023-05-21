<?php

namespace App\Repositories;

use App\Models\AdminUser;
use App\Repositories\BaseRepository;

/**
 * Class AdminUserRepository
 * @package App\Repositories
 * @version May 20, 2023, 2:51 pm UTC
*/

class AdminUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'username',
        'email',
        'password',
        'status',
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
        return AdminUser::class;
    }
}
