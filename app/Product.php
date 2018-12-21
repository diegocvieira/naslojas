<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use FullTextSearch;
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['store_id', 'title', 'slug', 'description', 'price', 'old_price', 'status', 'installment', 'gender', 'installment_price', 'related'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $searchable = ['title'];

    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }

    public function images()
    {
        return $this->hasMany('App\ProductImage', 'product_id', 'id')->orderBy('position', 'ASC');
    }

    public function sizes()
    {
        return $this->hasMany('App\ProductSize', 'product_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'product_id', 'id')->orderBy('id', 'DESC');
    }

    public function scopeFilterGender($query, $gender)
    {
        if ($gender && $gender != 'todos' && $gender != 'unissex') {
            $gender = $gender == 'masculino' ? 3 : 2;

            return $query->where('gender', $gender);
        }
    }

    public function scopeFilterOrder($query, $order)
    {
        if ($order && $order != 'palavra-chave') {
            if ($order == 'maior_preco') {
                return $query->orderBy('price', 'DESC');
            } else if ($order == 'menor_preco') {
                return $query->orderBy('price', 'ASC');
            } else {
                return $query->orderBy('pageviews', 'DESC');
            }
        }
    }

	protected static function boot()
	{
	    parent::boot();

		static::addGlobalScope('active', function(Builder $builder) {
	        $builder->where(function ($builder) {
	        	$builder->where('status', 1);
	        });
	    });

        static::addGlobalScope('active-store', function(Builder $builder) {
	        $builder->whereHas('store', function ($builder) {
	        	$builder->where('status', 1);
	        });
	    });
	}
}
