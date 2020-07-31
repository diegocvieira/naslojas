<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;

    protected $table = 'tokens';
    protected $fillable = ['token'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
