<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'cpf', 'phone', 'city_id', 'district_id', 'cep', 'street', 'number', 'complement', 'birthdate'];
    protected $hidden = ['password'];
    protected $dates = ['created_at', 'updated_at'];

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo('App\District', 'district_id', 'id');
    }
}
