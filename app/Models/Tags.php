<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Tags
 * @package App\Models
 * @version May 25, 2023, 9:38 am UTC
 *
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 */
class Tags extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'tags';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|array'
    ];


}
