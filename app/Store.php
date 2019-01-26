<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use FullTextSearch;

    protected $table = 'stores';
    protected $fillable = ['city_id', 'name', 'cep', 'district', 'street', 'number', 'complement', 'slug', 'status', 'reserve'];
    protected $dates = ['created_at', 'updated_at'];
    protected $searchable = ['name'];

    public function superadmin()
    {
        return $this->hasMany('App\SuperAdminStore', 'store_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'store_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }

    public function user()
    {
        return $this->hasMany('App\User', 'store_id', 'id');
    }
}
