<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReserve extends Model
{
    protected $table = 'product_reserves';
    protected $fillable = ['product_id', 'client_id', 'confirmed_at', 'reserved_until', 'status', 'size', 'token'];
    protected $dates = ['created_at', 'updated_at', 'confirmed_at', 'reserved_until'];

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }
}
