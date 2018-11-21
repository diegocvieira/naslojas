<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;

class FiltersComposer
{
	public function compose(View $view)
	{
        $genders = [
            'unissex' => 'Unissex',
            'masculino' => 'Masculino',
            'feminino' => 'Feminino'
        ];

        $order = [
            'populares' => 'Populares',
            'menor_preco' => 'Menor preço',
            'maior_preco' => 'Maior preço'
        ];

        $view->with('genders', $genders)->with('orderby', $order);
	}
}
