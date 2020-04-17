<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use App\District;

class DistrictsComposer
{
	public function compose(View $view)
	{
		$districts = District::orderBy('name', 'ASC')->get();

        $view->with('districts', $districts);
	}
}
