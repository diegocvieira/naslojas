<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $fillable = ['title', 'letter', 'iso', 'slug', 'population'];
    public $timestamps = false;
}
