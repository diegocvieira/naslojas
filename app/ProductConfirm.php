<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductConfirm extends Model
{
    protected $table = 'product_confirms';
    protected $fillable = ['product_id', 'client_id', 'confirmed_at', 'status', 'size', 'token'];
    protected $dates = ['created_at', 'updated_at', 'confirmed_at'];

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }
}
