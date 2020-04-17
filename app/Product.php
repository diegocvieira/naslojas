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
    protected $fillable = ['store_id', 'title', 'slug', 'description', 'price', 'off', 'status', 'gender', 'related', 'free_freight'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $searchable = ['title', 'identifier'];

    public function showParcels($product)
    {
        $parcels = 0;

        for ($i = 2; $i <= $product->store->max_parcel; $i++) {
            if (($product->price / $i) >= $product->store->min_parcel_price) {
                $parcels = $i;
            }
        }

        if ($parcels) {
            return 'em até ' . $parcels . 'x de R$ ' . number_format($product->price / $parcels, 2, ',', '.') . ' sem juros';
        } else {
            return null;
        }
    }

    public function offtime()
    {
        return $this->hasOne('App\OffTime', 'product_id', 'id');
    }

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

    public function scopeFilterInstallment($query, $installment)
    {
        if ($installment) {
            return $query->whereHas('store', function ($q) use ($installment) {
                $q->where('max_parcel', '>=', $installment);
            });
        }
    }

    public function scopeFilterColor($query, $color)
    {
        if ($color) {
            if ($color == 'preto') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%preto%')
                        ->orWhere('title', 'LIKE', '%black%')
                        ->orWhere('title', 'LIKE', '%preta%');
                });
            } else if ($color == 'branco') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%branco%')
                        ->orWhere('title', 'LIKE', '%white%')
                        ->orWhere('title', 'LIKE', '%branca%');
                });
            } else if ($color == 'cinza') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%cinza%')
                        ->orWhere('title', 'LIKE', '%cinza fraco%')
                        ->orWhere('title', 'LIKE', '%cinza forte%')
                        ->orWhere('title', 'LIKE', '%cinza claro%')
                        ->orWhere('title', 'LIKE', '%cinza escuro%')
                        ->orWhere('title', 'LIKE', '%cinza chumbo%')
                        ->orWhere('title', 'LIKE', '%grafite%')
                        ->orWhere('title', 'LIKE', '%prata%')
                        ->orWhere('title', 'LIKE', '%grey%');
                });
            } else if ($color == 'marrom') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%marrom%')
                        ->orWhere('title', 'LIKE', '%marrom fraco%')
                        ->orWhere('title', 'LIKE', '%marrom claro%')
                        ->orWhere('title', 'LIKE', '%marrom escuro%')
                        ->orWhere('title', 'LIKE', '%brown%');
                });
            } else if ($color == 'bege') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%bege%')
                        ->orWhere('title', 'LIKE', '%nude%')
                        ->orWhere('title', 'LIKE', '%beige%');
                });
            } else if ($color == 'azul') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%azul%')
                        ->orWhere('title', 'LIKE', '%azul forte%')
                        ->orWhere('title', 'LIKE', '%azul escuro%')
                        ->orWhere('title', 'LIKE', '%azul marinho%')
                        ->orWhere('title', 'LIKE', '%blue%')
                        ->orWhere('title', 'LIKE', '%navy blue%');
                });
            } else if ($color == 'azul claro') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%azul claro%')
                        ->orWhere('title', 'LIKE', '%azul fraco%')
                        ->orWhere('title', 'LIKE', '%azul bebe%')
                        ->orWhere('title', 'LIKE', '%baby blue%');
                });
            } else if ($color == 'verde') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%verde%')
                        ->orWhere('title', 'LIKE', '%verde forte%')
                        ->orWhere('title', 'LIKE', '%verde escuro%')
                        ->orWhere('title', 'LIKE', '%verde militar%')
                        ->orWhere('title', 'LIKE', '%verde musgo%')
                        ->orWhere('title', 'LIKE', '%green%');
                });
            } else if ($color == 'verde claro') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%verde fraco%')
                        ->orWhere('title', 'LIKE', '%verde claro%')
                        ->orWhere('title', 'LIKE', '%verde limao%')
                        ->orWhere('title', 'LIKE', '%verde neon%');
                });
            } else if ($color == 'amarelo') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%amarelo%')
                        ->orWhere('title', 'LIKE', '%amarelo ouro%')
                        ->orWhere('title', 'LIKE', '%yellow%')
                        ->orWhere('title', 'LIKE', '%amarela%');
                });
            } else if ($color == 'vermelho') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%vermelho%')
                        ->orWhere('title', 'LIKE', '%red%')
                        ->orWhere('title', 'LIKE', '%rouge%')
                        ->orWhere('title', 'LIKE', '%vermelho fraco%')
                        ->orWhere('title', 'LIKE', '%vermelho forte%')
                        ->orWhere('title', 'LIKE', '%vermelho escuro%')
                        ->orWhere('title', 'LIKE', '%vermelho claro%')
                        ->orWhere('title', 'LIKE', '%vermelha%');
                });
            } else if ($color == 'laranja') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%laranja%')
                        ->orWhere('title', 'LIKE', '%laranja claro%')
                        ->orWhere('title', 'LIKE', '%laranja fraco%')
                        ->orWhere('title', 'LIKE', '%laranja forte%')
                        ->orWhere('title', 'LIKE', '%laranja escuro%')
                        ->orWhere('title', 'LIKE', '%laranja neon%')
                        ->orWhere('title', 'LIKE', '%orange%');
                });
            } else if ($color == 'roxo') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%roxo%')
                        ->orWhere('title', 'LIKE', '%roxo fraco%')
                        ->orWhere('title', 'LIKE', '%roxo forte%')
                        ->orWhere('title', 'LIKE', '%roxo claro%')
                        ->orWhere('title', 'LIKE', '%roxo escuro%')
                        ->orWhere('title', 'LIKE', '%purple%')
                        ->orWhere('title', 'LIKE', '%roxa%');
                });
            } else if ($color == 'rosa') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%rosa%')
                        ->orWhere('title', 'LIKE', '%rosa fraco%')
                        ->orWhere('title', 'LIKE', '%rosa forte%')
                        ->orWhere('title', 'LIKE', '%rosa claro%')
                        ->orWhere('title', 'LIKE', '%rosa escuro%')
                        ->orWhere('title', 'LIKE', '%rosa pink%')
                        ->orWhere('title', 'LIKE', '%pink%');
                });
            } else if ($color == 'lilás') {
                return $query->where(function ($q) {
                    $q->where('title', 'LIKE', '%lilás%');
                });
            }
        }
    }

    public function scopeFilterFreight($query, $freight)
    {
        if ($freight) {
            return $query->where('free_freight', '1');
        }
    }

    public function scopeFilterCategory($query, $category)
    {
        if ($category) {
            return $query->where('title', 'LIKE', '%' . $category . '%');
        }
    }

    public function scopeFilterBrand($query, $brand)
    {
        if ($brand) {
            return $query->where('title', 'LIKE', '%' . $brand . '%');
        }
    }

    public function scopeFilterOff($query, $off)
    {
        if ($off) {
            return $query->where('off', '>=', $off);
        }
    }

    public function scopeFilterSize($query, $size)
    {
        if ($size) {
            return $query->whereHas('sizes', function ($q) use ($size) {
                $q->where('size', $size);
            });
        }
    }

    public function scopeFilterMinPrice($query, $min)
    {
        if ($min) {
            return $query->where('price', '>=', $min);
        }
    }

    public function scopeFilterMaxPrice($query, $max)
    {
        if ($max && $max != '0') {
            return $query->where('price', '<=', $max);
        }
    }

    public function scopeFilterGender($query, $gender)
    {
        if ($gender && $gender != 'unissex') {
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
	        })
            ->has('sizes');
	    });

        static::addGlobalScope('active-store', function(Builder $builder) {
	        $builder->whereHas('store', function ($builder) {
	        	$builder->isActive();
	        });
	    });
	}
}
