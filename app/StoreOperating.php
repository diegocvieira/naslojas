<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreOperating extends Model
{
    protected $table = 'stores_operating';
    protected $fillable = ['store_id', 'week', 'opening_morning', 'closed_morning', 'opening_afternoon', 'closed_afternoon'];
    public $timestamps = false;
}
