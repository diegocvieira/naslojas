<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $table = 'product_ratings';
    protected $fillable = ['product_id', 'client_id', 'rating', 'created_at'];
    protected $dates = ['created_at'];
    public $timestamps = false;
}
