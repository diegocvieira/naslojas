<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuperAdminStore extends Model
{
    protected $table = 'superadmin_stores';
    protected $fillable = ['superadmin_id', 'store_id'];
    public $timestamps = false;
}
