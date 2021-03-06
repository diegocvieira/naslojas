<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'client_ip',
        'client_name',
        'payment',
        'client_phone',
        'client_cpf',
        'client_city_id',
        'client_district_id',
        'client_cep',
        'client_street',
        'client_number',
        'client_complement',
        'freight'
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\OrderProducts', 'order_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo('App\District', 'client_district_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'client_city_id', 'id');
    }
}
