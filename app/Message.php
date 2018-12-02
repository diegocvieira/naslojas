<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['product_id', 'client_id', 'question', 'answered_at', 'response', 'status'];
    protected $dates = ['created_at', 'updated_at', 'answered_at'];

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
