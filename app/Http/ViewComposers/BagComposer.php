<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use Session;

class BagComposer
{
	public function compose(View $view)
	{
        $count = 0;

		if (Session::has('bag')) {
			foreach (session('bag')['stores'] as $store) {
				$count += count($store['products']);
			}
		} else {
            $count = null;
        }

        $view->with('count_bag', $count);
	}
}
