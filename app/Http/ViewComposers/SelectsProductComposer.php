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
		for ($z = 8; $z <= 112; $z++) {
			if (!isset($next)) {
				$next = $z;
			}

			$size_numbers[$z] = $next;

			$next = is_numeric($next) ? $next . '/' . ($next + 1) : explode('/', $next)[1];
		}
        $size_letters = [
            'Ú' => 'Ú',
            'PP' => 'PP',
            'P' => 'P',
            'M' => 'M',
            'G' => 'G',
            'GG' => 'GG',
            'XG' => 'XG',
			'2G' => '2G',
			'3G' => '3G',
            'XGG' => 'XGG',
            'XXG' => 'XXG'
        ];

        $view->with('size_letters', $size_letters)
            ->with('size_numbers', $size_numbers)
            ->with('genders', $genders);
	}
}
