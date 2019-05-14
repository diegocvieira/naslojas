<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use App\ProductSize;

class FiltersComposer
{
	public function compose(View $view)
	{
        $genders = [
            'unissex' => 'unissex',
            'masculino' => 'masculino',
            'feminino' => 'feminino'
        ];

        $order = [
            'populares' => 'Populares',
            'menor_preco' => 'Menor preço',
            'maior_preco' => 'Maior preço'
        ];

		$sizes = ProductSize::select('size')
			->distinct()
			->orderBy('size', 'ASC')
			->get();

        $view->with('genders', $genders)
			->with('orderby', $order)
			->with('sizes', $sizes);
	}
}
