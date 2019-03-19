<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use FullTextSearch;

    protected $table = 'stores';
    protected $fillable = ['city_id', 'name', 'cep', 'district', 'street', 'number', 'complement', 'slug', 'status', 'min_parcel_price', 'max_parcel', 'max_product_unit', 'cnpj', 'phone'];
    protected $dates = ['created_at', 'updated_at'];
    protected $searchable = ['name'];

    public function superadmin()
    {
        return $this->hasMany('App\SuperAdminStore', 'store_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\PaymentMethods', 'store_id', 'id');
    }

    public function freights()
    {
        return $this->hasMany('App\StoreFreight', 'store_id', 'id');
    }

    /*public function operatings()
    {
        return $this->hasMany('App\StoreOperating', 'store_id', 'id');
    }*/

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
