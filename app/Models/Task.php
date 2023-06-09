<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Task
 * @package App\Models
 * @version May 20, 2023, 3:16 pm UTC
 *
 * @property string $name
 * @property array $adv_id
 * @property integer $peak_price
 * @property numeric $min_roi
 * @property integer $is_allow_bulk
 * @property integer $punish
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Task extends BaseModel
{
    use SoftDeletes;

    use HasFactory;

    const STATUS_IN_PROGRESS = 'inProgress';
    const STATUS_PAUSE = 'pause';

    // 是否允许放量
    const NOT_ALLOW_BULK = 0;

    // 处罚规则
    const PUNISH_PAUSE = 'pause';
    const PUNISH_DELETE = 'delete';


    public $table = 'tasks';


    protected $dates = ['deleted_at'];

    protected $appends = ['adv_list', 'scanned'];

    public $fillable = [
        'name',
        'adv_id',
        'peak_price',
        'min_roi',
        'is_allow_bulk',
        //'is_allow_unbind',
        'punish',
        'status',
        'marketing_goal',
        'scanned_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'peak_price' => 'integer',
        'min_roi' => 'double',
        'is_allow_bulk' => 'boolean',
        //'is_allow_unbind' => 'boolean',
        'punish' => 'string',
        'status' => 'string'
    ];

    public function scopeOrderById($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:50',
        'adv_id' => 'required|array',
        'peak_price' => 'required|integer',
        'min_roi' => 'required|numeric',
        'is_allow_bulk' => 'required|boolean',
        // 'is_allow_unbind' => 'required|boolean',
        'punish' => 'required|string|in:pause,delete',
        'status' => 'required|string|in:pause,inProgress',
        'marketing_goal' => 'required|string|in:LIVE_PROM_GOODS,VIDEO_PROM_GOODS',
    ];

    public function setAdvIdAttribute($value)
    {
        $this->attributes['adv_id'] = json_encode($value);
    }

    public function getAdvIdAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getAdvListAttribute()
    {
        $advId = json_decode($this->attributes['adv_id'], true);
        return Shops::whereIn('advertiser_id', $advId)->select('id', 'advertiser_id', 'advertiser_name')->get();
    }

    public function getScannedAttribute($value): ?array
    {
        if (isset($this->attributes['scanned_at'])) {
            $now = Carbon::now();
            $scannedTime = new Carbon($this->attributes['scanned_at']);
            $diff = $scannedTime->diffInSeconds($now);
            if ($diff < 60) {
                return [
                    'label' => '<60s',
                    'key' => 1,
                ];
            } elseif ($diff > 100 && $diff <= 900) {
                return [
                    'label' => '<' . (int)($diff / 60) . 'm',
                    'key' => 1,
                ];
            } else {
                return [
                    'label' => '>15m',
                    'key' => 2
                ];
            }
        }
        return null;
    }


}
