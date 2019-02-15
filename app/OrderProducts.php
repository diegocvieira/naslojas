<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $table = 'order_products';
    protected $fillable = ['order_id', 'product_id', 'confirmed_at', 'status', 'title', 'qtd', 'price', 'size', 'image'];
    protected $dates = ['created_at', 'updated_at', 'confirmed_at'];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
