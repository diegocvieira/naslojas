<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $fillable = ['country_id', 'region_id', 'title', 'letter', 'iso', 'status', 'slug', 'letter_lc', 'order'];
    public $timestamps = false;
}
