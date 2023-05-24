<?php

namespace App\Repositories;

use App\Models\Ads;
use App\Repositories\BaseRepository;

/**
 * Class AdsRepository
 * @package App\Repositories
 * @version May 24, 2023, 10:29 am UTC
*/

class AdsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ad_id',
        'ad_create_time',
        'ad_modify_time',
        'lab_ad_type',
        'marketing_goal',
        'marketing_scene',
        'name',
        'status',
        'opt_status',
        'aweme_id',
        'aweme_name',
        'aweme_show_id',
        'aweme_avatar',
        'deep_external_action',
        'deep_bid_type',
        'roi_goal',
        'cpa_bid',
        'start_time',
        'end_time'
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
        return Ads::class;
    }
}
