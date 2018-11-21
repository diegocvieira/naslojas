<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['email', 'password', 'store_id'];
    protected $hidden = ['password'];
    protected $dates = ['created_at', 'updated_at'];

    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }
}
