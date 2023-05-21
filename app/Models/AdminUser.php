<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
class AdminUser extends Model
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


}
