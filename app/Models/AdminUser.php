<?php

namespace App\Models;

use Eloquent as Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class AdminUser
 * @package App\Models
 * @version May 20, 2023, 2:51 pm UTC
 *
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class AdminUser extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'admin_users';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'username',
        'email',
        'password',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'username' => 'string',
        'email' => 'string',
        'password' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'username' => 'required',
        'email' => 'required',
        'password' => 'required',
        'status' => 'required'
    ];

    /**
     * 获取用户省份标识
     *
     * Date: 2021/6/5
     * @return mixed
     * @author George <george@betterde.com>
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 获取自定义 Claims
     *
     * Date: 2021/6/5
     * @return array
     * @author George <george@betterde.com>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }


}
