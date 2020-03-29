<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['state_id', 'title', 'iso', 'iso_ddd', 'status', 'slug', 'population', 'lat', 'long', 'income_per_capita'];
    public $timestamps = false;

    public function stores()
    {
        return $this->hasMany('App\Store', 'city_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_id', 'id');
    }
}
