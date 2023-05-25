<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AlarmLogs
 * @package App\Models
 * @version May 25, 2023, 9:24 am UTC
 *
 * @property integer $task_id
 * @property integer $adver_id
 * @property string $adver_name
 * @property integer $is_valid
 * @property integer $shop_id
 * @property string $ad_name
 * @property string $punish_rule
 * @property boolean $exec_result
 * @property string $created_at
 * @property string $type
 * @property string $updated_at
 */
class AlarmLogs extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'alarm_logs';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'task_id',
        'adver_id',
        'adver_name',
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'task_id' => 'integer',
        'adver_id' => 'integer',
        'adver_name' => 'string',
        'is_valid' => 'integer',
        'shop_id' => 'integer',
        'ad_name' => 'string',
        'punish_rule' => 'string',
        'type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'shop_id' => 'ad_id integer',
        'ad_name' => 'cause string text',
        'created_at' => 'updated_at date date'
    ];


}
