<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';
    protected $fillable = ['city_id', 'name', 'cep', 'district', 'street', 'number', 'complement', 'slug', 'status', 'reserve'];
    protected $dates = ['created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany('App\Product', 'store_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }
}
