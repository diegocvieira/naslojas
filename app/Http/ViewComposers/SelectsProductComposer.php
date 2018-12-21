<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;

class SelectsProductComposer
{
	public function compose(View $view)
	{
        $genders = [
            '1' => 'unissex',
            '2' => 'feminino',
            '3' => 'masculino'
        ];

        // Sizes
        for($z = 16; $z <= 54; $z++) {
            $size_numbers[$z] = $z;
        }
        $size_letters = [
            'Ú' => 'Ú',
            'PP' => 'PP',
            'P' => 'P',
            'M' => 'M',
            'G' => 'G',
            'GG' => 'GG',
            'XG' => 'XG',
            'XGG' => 'XGG',
            'XXG' => 'XXG'
        ];

        $view->with('size_letters', $size_letters)
            ->with('size_numbers', $size_numbers)
            ->with('genders', $genders);
	}
}
