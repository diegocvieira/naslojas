<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffTime extends Model
{
    protected $table = 'offs_time';
    protected $fillable = ['product_id', 'off', 'time'];
    protected $dates = ['created_at', 'updated_at'];
}
