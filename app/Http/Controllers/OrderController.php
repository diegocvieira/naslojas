<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderProducts;
use App\Order;
use App\ProductSize;
use App\Product;
use Auth;
use Agent;
use Mail;

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

        $section = 'order';

        $orders = Order::where('client_id', Auth::guard('client')->user()->id)
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
            return view('client.orders', compact('orders', 'header_title'));
        } else {
            return view('mobile.client.orders', compact('orders', 'header_title', 'section'));
        }
    }

    public function storeOrders()
    {
        $header_title = 'Pedidos | naslojas.com';

        $section = 'order';

        $orders = Order::where('store_id', $this->store_id)
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
            return view('store.orders', compact('orders', 'header_title', 'section'));
        } else {
            return view('mobile.store.orders', compact('orders', 'header_title', 'section'));
        }
    }

    public function searchStoreOrders(Request $request)
    {
        $header_title = 'Pedidos | naslojas.com';

        $section = 'order';

        $keyword = $request->keyword;

        $orders = Order::where('store_id', $this->store_id)
            ->whereHas('products.product', function ($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            })
            ->with(['products.product' => function($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->where(function($query) use ($keyword) {
                $query->whereHas('products.product', function ($query) use ($keyword) {
                        $query->where('title', 'LIKE', '%' . $keyword . '%');
                    })
                    ->orWhereHas('store', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%');
                    })
                    ->orWhereHas('client', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        if (Agent::isDesktop()) {
            return view('store.orders', compact('orders', 'keyword', 'header_title', 'section'));
        } else {
            return view('mobile.store.orders', compact('orders', 'keyword', 'header_title', 'section'));
        }
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
            $return['msg'] = 'Lembre-se de ligar para o cliente para combinar o melhor horário para a entrega.';

            Mail::send('emails.order-confirm', [], function ($q) use ($order) {
                $q->from('no-reply@naslojas.com', 'naslojas');
                $q->to($order->order->client->email);
                $q->subject('Pedido confirmado');
            });
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
            ProductSize::whereHas('product', function ($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                })
                ->where('product_id', $order->product_id)
                ->where('size', $order->size)
                ->delete();

            $product = Product::doesnthave('sizes')
                ->withTrashed()
                ->withoutGlobalScopes(['active', 'active-store'])
                ->where('id', $order->product_id)
                ->first();

            if ($product) {
                $product->status = 0;
                $product->save();
            }

            // Calculate free freight
            $ffs = OrderProducts::whereHas('product', function ($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                })
                ->with(['product' => function($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                }])
                ->where(function($q) {
                    $q->where('status', 2)
                        ->orWhere('status', 1);
                })
                ->where('order_id', $order->order_id)
                ->get();

            $free_freight_count = 0;

            foreach ($ffs as $ff) {
                if ($ff->product->free_freight) {
                    $free_freight_count++;
                }
            }

            if ($free_freight_count == $ffs->count()) {
                $o = Order::find($order->order_id);
                $o->freight = 0.00;
                $o->save();
            }
            // End calculate free freight

            $return['status'] = true;
            $return['msg'] = 'Mantenha seus produtos atualizados. <br> Isso evita que sua loja perca pontos de relevância e seus produtos caiam de posição nas buscas.';

            Mail::send('emails.order-refuse', [], function ($q) use ($order) {
                $q->from('no-reply@naslojas.com', 'naslojas');
                $q->to($order->order->client->email);
                $q->subject('Pedido recusado');
            });
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }
}
