<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class shops
 * @package App\Models
 * @version May 21, 2023, 3:54 pm UTC
 *
 * @property integer $parent_id
 * @property integer $advertiser_id
 * @property string $advertiser_name
 * @property boolean $is_valid
 * @property string $account_role
 * @property string $company
 * @property string $first_name
 * @property string $second_name
 * @property string $access_token
 * @property string $refresh_token
 * @property integer $access_token_expires_at
 * @property integer $refresh_token_expires_at
 * @property string $created_at
 * @property string $updated_at
 */
class Shops extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'shops';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'parent_id',
        'advertiser_id',
        'advertiser_name',
        'company',
        'first_name',
        'second_name',
        'is_valid',
        'account_role',
        'access_token',
        'refresh_token',
        'access_token_expires_at',
        'refresh_token_expires_at',
        'created_at',
        'updated_at',
        'has_child'
    ];

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
        'account_role' => 'string',
    ];

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


}
