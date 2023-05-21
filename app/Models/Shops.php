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
 * @property integer $advertiser_id
 * @property string $advertiser_name
 * @property boolean $is_valid
 * @property string $account_role
 * @property string $created_at
 * @property string $updated_at
 * @property integer $has_child
 */
class Shops extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'shops';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'advertiser_id',
        'advertiser_name',
        'is_valid',
        'account_role',
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
        'advertiser_id' => 'integer',
        'advertiser_name' => 'string',
        'is_valid' => 'boolean',
        'account_role' => 'string',
        'has_child' => 'integer'
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
