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

    /**
     * @param array $item
     * @return array
     */
    public function getInputFields(array $item): array
    {
        return [
            'ad_id' => $item['ad_id'],
            'name' => $item['name'],
            'status' => $item['status'],
            'opt_status' => $item['opt_status'],
            'ad_create_time' => $item['ad_create_time'],
            'ad_modify_time' => $item['ad_modify_time'],
            'lab_ad_type' => $item['lab_ad_type'],
            'marketing_goal' => $item['marketing_goal'],
            // 'marketing_scene' => $item['marketing_scene'],
            'aweme_id' => $item['aweme_info']['aweme_id'] ?? 0,
            'aweme_name' => $item['aweme_info']['aweme_name'] ?? '',
            'aweme_show_id' => $item['aweme_info']['aweme_show_id'] ?? '',
            'aweme_avatar' => $item['aweme_info']['aweme_avatar'] ?? '',
            'deep_external_action' => $item['delivery_setting']['deep_external_action'] ?? '',
            'deep_bid_type' => $item['delivery_setting']['deep_bid_type'] ?? '',
            'roi_goal' => $item['delivery_setting']['roi_goal'] ?? 0,
            'cpa_bid' => $item['delivery_setting']['cpa_bid'] ?? 0,
            'start_time' => $item['delivery_setting']['start_time'],
            'end_time' => $item['delivery_setting']['end_time'] ?? '',
        ];
    }


    public function search($advId, $adId, $adName, $paginate = true, $perPage = 15)
    {
        $query = $this->model->query();

        if ($advId !== null) {
            $query->where('adv_id', $advId);
        }
        if ($adId !== null) {
            $query->where('ad_id', $adId);
        }
        if ($adName !== null) {
            $query->where('name', 'like', "%$adName%");
        }

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
