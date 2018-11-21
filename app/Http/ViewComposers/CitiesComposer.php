<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use App\City;

class CitiesComposer
{
	public function compose(View $view)
	{
		// List cities them have at least one product
        /*$city = City::has('stores.products')->orderBy('title', 'ASC')->get();

		foreach ($city as $c) {
			$cities[$c->id] = $c->title . '/' . $c->state->letter;
		}

		asort($cities);

        $view->with('cities', $cities);*/
	}
}
