<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Session;
use Agent;

class CentralController extends Controller
{
    public function getLogin()
    {
        $top_simple = true;
        $header_title = 'Login da central | naslojas.com';

        if (Agent::isDesktop()) {
            return view('central.login', compact('header_title', 'top_simple'));
        } else {
            return view('mobile.central.login', compact('header_title', 'top_simple'));
        }
    }

    public function postLogin(Request $request)
    {
        if ($request->email == 'central@naslojas.com' && $request->password == '@central2019') {
            session(['central_logged' => true]);

            return redirect()->route('central-orders');
        } else {
            Session::flash('session_flash_alert', 'E-mail ou senha inválidos.');

            return redirect()->route('central-login')->withInput($request->all());
        }
    }

    public function orders()
    {
        if (Session::has('central_logged')) {
            $top_simple = true;

            $orders = Order::whereHas('products', function ($query) {
                    $query->where('status', 1);
                })
                ->whereHas('products.product', function ($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                })
                ->with(['products.product' => function($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                }])
                ->orderByDesc('created_at')
                ->paginate(20);

            if (Agent::isDesktop()) {
                return view('central.orders', compact('orders', 'top_simple'));
            } else {
                $body_class = 'bg-white';

                return view('mobile.central.orders', compact('orders', 'top_simple', 'body_class'));
            }
        } else {
            return redirect()->route('central-login');
        }
    }
}
