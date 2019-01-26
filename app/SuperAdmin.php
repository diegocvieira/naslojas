<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    protected $table = 'superadmins';
    protected $fillable = ['email', 'password', 'type'];
    protected $hidden = ['password'];
    protected $dates = ['created_at', 'updated_at'];
}
