<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s';


    /**
     * 将 "created_at" 字段的值转换为指定日期格式并返回。
     *
     * @param mixed $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date($this->dateFormat, strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date($this->dateFormat, strtotime($value));
    }
}
