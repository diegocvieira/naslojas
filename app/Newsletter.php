<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletter';
    protected $fillable = ['email'];
    protected $dates = ['created_at', 'updated_at'];
}
