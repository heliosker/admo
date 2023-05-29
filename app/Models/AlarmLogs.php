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
 * @property integer $ad_id
 * @property string $ad_name
 * @property integer $adv_id
 * @property integer $task_id
 * @property string $task_name
 * @property integer $is_valid
 * @property string $punish_rule
 * @property boolean $exec_result
 * @property string $created_at
 * @property string $type
 * @property string $cause
 * @property string $updated_at
 */
class AlarmLogs extends BaseModel
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'alarm_logs';

    const TYPE_TASK = 1;
    const TYPE_UNBIND = 2;

    public $fillable = [
        'task_id',
        'task_name',
        'adv_id',
        'is_valid',
        'ad_name',
        'ad_id',
        'punish_rule',
        'exec_result',
        'type',
        'cause',
        'created_at',
        'updated_at'
    ];


    protected $dates = ['deleted_at'];
    protected $appends = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'task_id' => 'integer',
        'adv_id' => 'integer',
        'is_valid' => 'integer',
        'ad_name' => 'string',
        'punish_rule' => 'string',
        'type' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ad_name' => 'cause string text',
        'created_at' => 'updated_at date date'
    ];

    public function advertiser(): ?\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shops::class,'adv_id','advertiser_id')
                ->select('advertiser_id','advertiser_name','is_valid','account_role','scanned_at') ?? null;
    }


}
