<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use App\City;

class CitiesComposer
{
	public function compose(View $view)
	{
        $cities = City::whereHas('stores', function ($query) {
                $query->isActive();
            })
            ->with('state')
            ->orderBy('title', 'ASC')
            ->get();

        $view->with('cities', $cities);
	}
}
