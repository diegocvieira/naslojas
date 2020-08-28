<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use Session;
use App\Http\Controllers\BagController;

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

        $cart = new BagController;
        $cartPreview = $cart->getCartDetails();

        $view->with('cartPreview', $cartPreview)
            ->with('count_bag', $count);
	}
}
