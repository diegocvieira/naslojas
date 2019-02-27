<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderProducts;
use Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('store')->check()) {
                $this->store_id = Auth::guard('store')->user()->store_id;
            } else if (Auth::guard('superadmin')->check()) {
                $this->store_id = session('superadmin_store_id');
            }

            return $next($request);
        });
    }

    public function clientOrders()
    {
        $header_title = 'Meus pedidos | naslojas.com';

        $products = OrderProducts::whereHas('order', function ($query) {
                $query->where('client_id', Auth::guard('client')->user()->id);
            })
            ->whereHas('product', function ($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            })
            ->with(['product' => function($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('client.orders', compact('products', 'header_title'));
    }

    public function storeOrders()
    {
        $header_title = 'Pedidos | naslojas.com';

        $section = 'order';

        $products = OrderProducts::whereHas('product', function ($query) {
                $query->where('store_id', $this->store_id)
                    ->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            })
            ->with(['product' => function($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('store.orders', compact('products', 'header_title', 'section'));
    }

    public function confirm($id)
    {
        $order = OrderProducts::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store'])
                    ->withTrashed()
                    ->where('store_id', $this->store_id);
            })
            ->where('id', $id)
            ->first();

        $date = date('Y-m-d H:i:s');

        $order->status = 1;

        if ($order->save()) {
            $return['status'] = true;
            $return['msg'] = 'Reserva realizada com sucesso! <br> O cliente já foi notificado de que o produto que ele deseja estará aguardando por ele na loja até o horário informado.';

            //$this->emailResponse($order, 1);
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function refuse($id)
    {
        $order = OrderProducts::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store'])
                    ->withTrashed()
                    ->where('store_id', $this->store_id);
            })
            ->where('id', $id)
            ->first();

        $date = date('Y-m-d H:i:s');

        $order->status = 0;

        if ($order->save()) {
            $return['status'] = true;
            $return['msg'] = 'Mantenha seus produtos atualizados. <br> Isso evita que sua loja perca os pontos de relevância e seus produtos caiam de posição nas buscas.';

            //$this->emailResponse($order, 1);
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }
}
