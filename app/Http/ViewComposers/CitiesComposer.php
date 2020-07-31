<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use App\City;
use Illuminate\Support\Facades\Cache;

class CitiesComposer
{
	public function compose(View $view)
	{
        $cities = Cache::rememberForever('cities', function () {
            return City::with(['state' => function ($query) {
                    $query->select('id', 'letter');
                }])
                ->select('id', 'title', 'state_id')
                ->orderBy('title', 'ASC')
                ->get();
        });

        $view->with('cities', $cities);
	}
}
