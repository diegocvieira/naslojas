<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Store;
use App\Client;
use App\District;
use App\Order;
use App\City;
use App\OrderProducts;
use Session;
use Auth;
use Validator;
use Agent;
use Mail;

class BagController extends Controller
{
    public function __construct()
    {
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    }

    public function add(Request $request)
    {
        $p = Product::find($request->product_id);

        $product_id = $p->id;
        $store_id = $p->store_id;
        $size = $request->size;
        $qtd = $request->qtd;

        if (Session::has('bag')) {
            $bag = session('bag');
        } else {
            $bag = ['stores' => []];

            session(['bag' => $bag]);
        }

        $store_exist = false;
        $product_exist = false;

        foreach ($bag['stores'] as $key => $store) {
            if ($store['store_id'] == $store_id) {
                $store_exist = true;
                $store_key = $key;

                foreach ($store['products'] as $key2 => $product) {
                    if ($product['id'] == $product_id && $product['size'] == $size && $product['qtd']) {
                        $product_exist = true;
                        $product_key = $key2;
                    }
                }
            }
        }

        if (!$product_exist) {
            if ($store_exist) {
                array_push($bag['stores'][$store_key]['products'], ['id' => $product_id, 'qtd' => $qtd, 'size' => $size]);
            } else {
                array_push($bag['stores'], ['store_id' => $store_id, 'products' => [['id' => $product_id, 'qtd' => $qtd, 'size' => $size]]]);
            }
        } else {
            $bag['stores'][$store_key]['products'][$product_key]['qtd'] = $qtd;
        }

        session(['bag' => $bag]);

        return json_encode(['success' => true]);
    }

    public function remove($product_id)
    {
        $bag = session('bag');

        foreach ($bag['stores'] as $key => $store) {
            foreach ($store['products'] as $key2 => $product) {
                if ($product['id'] == $product_id) {
                    unset($bag['stores'][$key]['products'][$key2]);

                    if (!count($bag['stores'][$key]['products'])) {
                        unset($bag['stores'][$key]);
                    }
                }
            }
        }

        session(['bag' => $bag]);

        if (!count($bag['stores'])) {
            Session::pull('bag');
        }

        return json_encode(true);
    }

    public function changeQtd($product_id, $qtd)
    {
        $bag = session('bag');

        foreach ($bag['stores'] as $key => $store) {
            foreach ($store['products'] as $key2 => $product) {
                if ($product['id'] == $product_id) {
                     $bag['stores'][$key]['products'][$key2]['qtd'] = $qtd;
                }
            }
        }

        session(['bag' => $bag]);

        return json_encode(true);
    }

    public function changeSize($product_id, $size)
    {
        $bag = session('bag');

        foreach ($bag['stores'] as $key => $store) {
            foreach ($store['products'] as $key2 => $product) {
                if ($product['id'] == $product_id) {
                     $bag['stores'][$key]['products'][$key2]['size'] = $size;
                }
            }
        }

        session(['bag' => $bag]);

        return json_encode(true);
    }

    public function changeDistrict($district_id)
    {
        foreach (session('bag')['stores'] as $store) {
            $store_ids[] = $store['store_id'];
        }

        $stores = Store::find($store_ids);

        foreach ($stores as $store) {
            $freights[] = $store->freights->where('district_id', $district_id)->first();
        }

        return json_encode(['freights' => $freights]);
    }

    public function products()
    {
        $cart = $this->getCartDetails();

        if (\Request::ajax()) {
            if (Agent::isDesktop()) {
                return response()->json([
                    'body' => view('bag.preview', compact('cart'))->render()
                ]);
            } else {
                return response()->json([
                    'body' => view('mobile.bag.preview', compact('cart'))->render()
                ]);
            }
        } else {
            if (Agent::isDesktop()) {
                return view('bag.products', compact('cart'));
            } else {
                return view('mobile.bag.products', compact('cart'));
            }
        }
    }

    public function data()
    {
        if (!Session::has('bag')) {
            return redirect()->route('home');
        }

        $cart = $this->getCartDetails();

        $client = Client::find(Auth::guard('client')->user()->id);

        $districts = District::where('city_id', $client->city_id)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id');

        if (Agent::isDesktop()) {
            return view('bag.order-data', compact('cart', 'client', 'districts'));
        } else {
            return view('mobile.bag.order-data', compact('cart', 'client', 'districts'));
        }
    }

    public function finish(Request $request)
    {
        $validate = Validator::make(
            $request->all(), [
                'cep' => 'required|max:10',
                'street' => 'required|max:200',
                'district' => 'required|max:100',
                'number' => 'required|max:15',
                'city' => 'required',
                'state' => 'required',
                'cpf' => 'required|max:15',
                'phone' => 'required|max:15',
                'payment' => 'required'
            ],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if ($validate->fails()) {
            $data['status'] = false;
            $data['msg'] = $validate->errors()->first();
            return response()->json($data);
        }

        $city = City::whereHas('state', function ($query) use ($request) {
                $query->where('letter', $request->state);
            })
            ->where('title', 'LIKE', '%' . $request->city . '%')
            ->select('id', 'slug')
            ->first();

        if (!$city || !$city->isAvailable()) {
            $data['status'] = false;
            $data['msg'] = 'Nossa entrega ainda não está disponível na sua região.';
            return response()->json($data);
        }

        $client_id = Auth::guard('client')->user()->id;

        $client = Client::find($client_id);
        $client->cpf = $request->cpf;
        $client->phone = $request->phone;
        $client->city_id = $city->id;
        $client->district_id = $request->district;
        $client->cep = $request->cep;
        $client->street = $request->street;
        $client->number = $request->number;
        $client->complement = $request->complement;
        $client->save();

        foreach (session('bag')['stores'] as $store) {
            foreach ($store['products'] as $product) {
                $products_id[] = $product['id'];
            }

            $products = Product::find($products_id);

            $order = new Order;
            $order->store_id = $store['store_id'];
            $order->client_id = $client_id;
            $order->client_ip = $request->client_ip;
            $order->client_name = $request->name;
            $order->client_phone = $request->phone;
            $order->client_cpf = $request->cpf;
            $order->payment = $request->payment == '0' ? '0-0' : $request->payment_card;
            $order->client_city_id = $city->id;
            $order->client_district_id = $request->district;
            $order->client_cep = $request->cep;
            $order->client_street = $request->street;
            $order->client_number = $request->number;
            $order->client_complement = $request->complement;
            $order->freight = !$products->where('free_freight', 0)->count() ? 0.00 : $products->first()->store->freights->where('district_id', $request->district)->first()->price;
            $order->save();

            $emails = [];

            foreach ($store['products'] as $product) {
                $p = Product::find($product['id']);

                // VERIFICA SE O PRODUTO POSSUI ALGUM DESCONTO
                if ($p->offtime && _checkDateOff($p->offtime->created_at, $p->offtime->time)) {
                    $p->price = _priceOff($p->price, $p->offtime->off);
                }

                $order->products()->create([
                    'size' => $product['size'],
                    'qtd' => $product['qtd'],
                    'image' => $p->images()->first()->image,
                    'price' => $p->price,
                    'title' => $p->title,
                    'product_id' => $p->id
                ]);

                $email = $p->store->user->first()->email;

                if (!in_array($email, $emails)) {
                    array_push($emails, $email);
                }
            }

            Mail::send('emails.order', [], function ($q) use ($emails) {
                $q->from('no-reply@naslojas.com', 'naslojas');
                $q->to($emails);
                $q->subject('Novo pedido de reserva');
            });

            Session::pull('bag');

            $data['status'] = true;
            $data['route'] = route('bag-success', $order->id);
        }

        return json_encode($data);
    }

    public function success($id)
    {
        $order = Order::findOrFail($id);

        if ($order->freight_type == 1) {
            $products = OrderProducts::whereHas('product', function ($q) {
                    $q->select('store_id');
                })
                ->select('product_id')
                ->where('order_id', $id)
                ->get();
        } else {
            $products = null;
        }

        $header_title = 'Pedido realizado - naslojas.com';

        if (Agent::isDesktop()) {
            return view('bag.success', compact('order', 'products', 'header_title'));
        } else {
            return view('mobile.bag.success', compact('order', 'products', 'header_title'));
        }
    }

    public function getCartDetails()
    {
        if (!session('bag')) {
            return false;
        }

        $cart['subtotal'] = 0;
        $cart['payments'] = [];

        foreach (session('bag')['stores'] as $keyStore => $cartStore) {
            $store = Store::with('freights', 'payments')->find($cartStore['store_id']);

            $cart['stores'][$keyStore]['subtotal'] = 0;
            $cart['stores'][$keyStore]['name'] = $store->name;
            $cart['stores'][$keyStore]['id'] = $store->id;
            $cart['stores'][$keyStore]['slug'] = $store->slug;
            $cart['stores'][$keyStore]['max_quantity'] = $store->max_product_unit;
            $cart['stores'][$keyStore]['min_parcel_price'] = $store->min_parcel_price;
            $cart['stores'][$keyStore]['max_parcel'] = $store->max_parcel;
            $cart['stores'][$keyStore]['free_freight'] = true;
            $cart['stores'][$keyStore]['freight'] = Auth::guard('client')->check() && Auth::guard('client')->user()->district_id ? $store->freights->where('district_id', Auth::guard('client')->user()->district_id)->first() : null;

            foreach ($store->payments as $payment) {
                $cart['payments'][] = $payment->method . '-' . $payment->payment;
            }

            foreach ($cartStore['products'] as $keyProduct => $cartProduct) {
                $product = Product::with('sizes', 'images')->find($cartProduct['id']);

                if (!$product) {
                    continue;
                }

                if (!$product->free_freight) {
                    $cart['stores'][$keyStore]['free_freight'] = false;
                }

                $cart['stores'][$keyStore]['products'][$keyProduct]['id'] = $product->id;
                $cart['stores'][$keyStore]['products'][$keyProduct]['name'] = $product->title;
                $cart['stores'][$keyStore]['products'][$keyProduct]['slug'] = $product->slug;
                $cart['stores'][$keyStore]['products'][$keyProduct]['image'] = $product->images()->first() ? $product->images()->first()->image : null;
                $cart['stores'][$keyStore]['products'][$keyProduct]['price'] = ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time)) ? _priceOff($product->price, $product->offtime->off) : $product->price;
                $cart['stores'][$keyStore]['products'][$keyProduct]['qtd'] = $cartProduct['qtd'];
                $cart['stores'][$keyStore]['products'][$keyProduct]['size'] = $cartProduct['size'];
                $cart['stores'][$keyStore]['products'][$keyProduct]['sizes'] = $product->sizes->pluck('size');

                $cart['stores'][$keyStore]['subtotal'] += $product->price * $cartProduct['qtd'];
            }

            if ($cart['stores'][$keyStore]['free_freight'] || $store->free_freight_price && $cart['stores'][$keyStore]['subtotal'] >= $store->free_freight_price) {
                $cart['stores'][$keyStore]['freight'] = 0.00;
            }

            $cart['subtotal'] += $cart['stores'][$keyStore]['subtotal'];
        }

        if (count(session('bag')['stores']) > 1) {
            // Get only duplicate values
            $cart['payments'] = array_diff_assoc($cart['payments'], array_unique($cart['payments']));
        }

        array_push($cart['payments'], '1-0', '1-1', '2-0', '2-1');

        return json_decode(json_encode($cart), false);
    }
}
