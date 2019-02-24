<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = ['store_id', 'method', 'payment'];
}
