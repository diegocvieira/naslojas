<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreFreight extends Model
{
    protected $table = 'stores_freight';
    protected $fillable = ['store_id', 'district_id', 'price'];
    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo('App\District', 'district_id', 'id');
    }
}
