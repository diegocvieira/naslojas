<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Users\Repository as UserRepository;
use Auth;
use App\Store;
use App\SuperAdminStore;

class ListStoresComposer
{
	public function compose(View $view)
	{
        if (Auth::guard('superadmin')->check()) {
            if (Auth::guard('superadmin')->user()->type == 1) {
                $stores = Store::orderBy('name', 'ASC')->get();
            } else {
				$stores = Store::whereHas('superadmin', function ($query) {
		                $query->where('superadmin_id', Auth::guard('superadmin')->user()->id);
		            })
					->orderBy('name', 'ASC')
					->get();
            }
        } else {
            $stores = null;
        }

        $view->with('superadmin_stores', $stores);
	}
}
