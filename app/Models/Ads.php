<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Ads
 * @package App\Models
 * @version May 24, 2023, 10:29 am UTC
 *
 * @property string $ad_id
 * @property string $adv_id 广告主ID
 * @property string $ad_create_time
 * @property string $ad_modify_time
 * @property string $lab_ad_type
 * @property string $marketing_goal
 * @property string $marketing_scene
 * @property string $name
 * @property string $status
 * @property string $opt_status
 * @property int $aweme_id
 * @property string $aweme_name
 * @property string $aweme_show_id
 * @property string $aweme_avatar
 * @property string $deep_external_action
 * @property string $deep_bid_type
 * @property number $roi_goal
 * @property number $cpa_bid
 * @property string $start_time
 * @property string $end_time
 */
class Ads extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'ads';

    // 计划状态
    const STATUS_DELIVERY_OK = 'DELIVERY_OK';   // 投放中
    const STATUS_AUDIT = 'AUDIT';       // 新建审核中
    const STATUS_REAUDIT = 'REAUDIT';       // 修改审核中
    const STATUS_TIME_DONE = 'TIME_DONE';   // 已完成
    const STATUS_DELETE = 'DELETE';   // 已删除
    const STATUS_DISABLE = 'DISABLE';   // 已暂停

    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ad_id' => 'string',
        'ad_create_time' => 'string',
        'ad_modify_time' => 'string',
        'lab_ad_type' => 'string',
        'marketing_goal' => 'string',
        'marketing_scene' => 'string',
        'name' => 'string',
        'status' => 'string',
        'opt_status' => 'string',
        'aweme_name' => 'string',
        'aweme_show_id' => 'string',
        'aweme_avatar' => 'string',
        'deep_external_action' => 'string',
        'deep_bid_type' => 'string',
        'roi_goal' => 'double',
        'cpa_bid' => 'double',
        'start_time' => 'string',
        'end_time' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];


}
