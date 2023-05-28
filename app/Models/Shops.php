<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Ramsey\Uuid\DeprecatedUuidMethodsTrait;

/**
 * Class shops
 * @package App\Models
 * @version May 21, 2023, 3:54 pm UTC
 *
 * @property integer $parent_id
 * @property integer $advertiser_id
 * @property string $advertiser_name
 * @property boolean $is_valid
 * @property integer $is_allow_unbind
 * @property string $account_role
 * @property string $company
 * @property string $first_name
 * @property string $second_name
 * @property string $access_token
 * @property string $refresh_token
 * @property string $mark
 * @property integer $access_token_expires_at
 * @property integer $refresh_token_expires_at
 * @property string $scanned_at
 * @property string $created_at
 * @property string $updated_at
 */
class Shops extends BaseModel
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'shops';

    protected $dates = ['deleted_at'];

    const MAIN_ACCOUNT = 0;
    // const SUB_ACCOUNT = 'sub';

    const ALLOW_UNBIND = 1;
    const NOT_ALLOW_UNBIND = 0;

    const UNKNOWN = -1; // 未知，默认值
    const INVALID = 0;  // 无效，已解绑
    const VALID = 1;    // 有效

    const ROLE_QIANCHUAN_AGENT = 'PLATFORM_ROLE_QIANCHUAN_AGENT';
    const ROLE_SHOP_ACCOUNT = 'PLATFORM_ROLE_SHOP_ACCOUNT';
    const ROLE_ADVERTISER = 'ADVERTISER';

    protected $fillable = [
        'parent_id',
        'advertiser_id',
        'advertiser_name',
        'company',
        'first_name',
        'second_name',
        'is_valid',
        'is_allow_unbind',
        'mark',
        'account_role',
        'access_token',
        'refresh_token',
        'access_token_expires_at',
        'refresh_token_expires_at',
        'scanned_at',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['role', 'valid', 'child_num', 'scanned'];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
        'advertiser_id' => 'integer',
        'advertiser_name' => 'string',
        'is_valid' => 'int',
        'account_role' => 'string'
    ];

    /**
     * 获取当前账户下 Access_token
     *
     * @return string
     */
    public function getShopAccessToken(): string
    {
        if ($this->parent_id != 0) {
            return self::where('id', $this->attributes['parent_id'])->value('access_token');
        }
        return $this->access_token;
    }

    /**
     * @return array
     */
    public function getRoleAttribute(): array
    {
        $role = $this->attributes['account_role'] ?? null;
        switch ($role) {
            case self::ROLE_QIANCHUAN_AGENT:
                $label = '代理商账户';
                break;
            case self::ROLE_SHOP_ACCOUNT:
                $label = '店铺账户';
                break;
            case self::ROLE_ADVERTISER:
                $label = '广告账户';
                break;
            default:
                $label = '未知';
                break;
        }
        return ['account_role' => $role, 'label' => $label];
    }

    /**
     * @return int
     */
    public function getChildNumAttribute(): int
    {
        return isset($this->attributes['id']) ? self::where('parent_id', $this->attributes['id'])->count() : 0;
    }

    /**
     * @return array
     */
    public function getValidAttribute(): array
    {
        $valid = $this->attributes['is_valid'] ?? null;
        switch ($valid) {
            case self::UNKNOWN:
                $label = '-';
                break;
            case self::INVALID:
                $label = '授权异常';
                break;
            case self::VALID:
                $label = '授权正常';
                break;
            default:
                $label = '未知';
        }
        return ['is_valid' => $valid, 'label' => $label];
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

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'advertiser_id' => 'required:integer',
        'advertiser_name' => 'required:string',
        'is_valid' => 'required:boolean',
        'account_role' => 'required:string'
    ];

    public function scopeMainAccount($query)
    {
        return $query->where('parent_id', self::MAIN_ACCOUNT);
    }

    public function scopeSubAccount($query)
    {
        return $query->where('parent_id', '!=', self::MAIN_ACCOUNT);
    }

}
