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
                    if ($product['id'] == $product_id && $product['qtd']) {
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
            $bag['stores'][$store_key]['products'][$product_key]['qtd'] = $qtd + (int)$bag['stores'][$store_key]['products'][$product_key]['qtd'];
        }

        session(['bag' => $bag]);

        return json_encode(['status' => true]);
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
        if (Session::has('bag')) {
            foreach (session('bag')['stores'] as $store) {
                foreach ($store['products'] as $product) {
                    $ids[] = $product['id'];
                }
            }

            $products = Product::find($ids);

            $subtotal = 0;

            foreach ($products as $key => $product) {
                // VERIFICA SE O PRODUTO POSSUI ALGUM DESCONTO
                if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time)) {
                    $product->price = _priceOff($product->price, $product->offtime->off);
                }

                foreach (session('bag')['stores'] as $bag_store) {
                    foreach ($bag_store['products'] as $bag_product) {
                        if ($bag_product['id'] == $product->id) {
                            $product->setAttribute('product_qtd', $bag_product['qtd']);
                            $product->setAttribute('size', $bag_product['size']);

                            $subtotal += $bag_product['qtd'] * $product->price;
                        }
                    }
                }

                $qtd = [];
                for ($i = 1; $i <= $product->store->max_product_unit; $i++) {
                    $qtd[$i] = $i;
                }

                $product->setAttribute('store_qtd', $qtd);
            }
        }

        if (\Request::ajax()) {
            if (Agent::isDesktop()) {
                return response()->json([
                    'body' => view('bag.preview', compact('products', 'subtotal'))->render()
                ]);
            } else {
                return response()->json([
                    'body' => view('mobile.bag.preview', compact('products', 'subtotal'))->render()
                ]);
            }
        } else {
            $header_title = 'Itens na sacola | naslojas.com';

            if (Agent::isDesktop()) {
                return view('bag.products', compact('products', 'subtotal', 'header_title'));
            } else {
                return view('mobile.bag.products', compact('products', 'subtotal', 'header_title'));
            }
        }
    }

    public function data()
    {
        if (!Session::has('bag')) {
            return redirect()->route('home');
        }

        $client = Client::find(Auth::guard('client')->user()->id);

        $districts = District::orderBy('name', 'ASC')->pluck('name', 'id');

        $header_title = 'Dados do pedido | naslojas.com';

        $payments = [];

        foreach (session('bag')['stores'] as $store_key => $store) {
            $bag_data[$store_key]['subtotal'] = 0;
            $bag_data[$store_key]['free_freight'] = true;

            foreach ($store['products'] as $p) {
                $product = Product::find($p['id']);

                // VERIFICA SE O PRODUTO POSSUI ALGUM DESCONTO
                if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time)) {
                    $product->price = _priceOff($product->price, $product->offtime->off);
                }

                if (!$product->free_freight) {
                    $bag_data[$store_key]['free_freight'] = false;
                }

                $bag_data[$store_key]['freight'] = $client->district_id ? $product->store->freights->where('district_id', $client->district_id)->first()->price : null;
                $bag_data[$store_key]['subtotal'] += $product->price * $p['qtd'];
                $bag_data[$store_key]['min_parcel_price'] = $product->store->min_parcel_price;
                $bag_data[$store_key]['max_parcel'] = $product->store->max_parcel;
                $bag_data[$store_key]['store'] = $product->store->name;
                $bag_data[$store_key]['city'] = $product->store->city->title;
                $bag_data[$store_key]['state'] = $product->store->city->state->letter;
                $bag_data[$store_key]['street'] = $product->store->street;
                $bag_data[$store_key]['district'] = $product->store->district;
                $bag_data[$store_key]['number'] = $product->store->number;
                $bag_data[$store_key]['complement'] = $product->store->complement;

                foreach ($product->store->payments as $payment) {
                    $payments[] = $payment->method . '-' . $payment->payment;
                }
            }

            if ($bag_data[$store_key]['free_freight']) {
                $bag_data[$store_key]['freight'] = 0.00;
            }
        }

        if (count(session('bag')['stores']) > 1) {
            // Get only duplicate values
            $payments = array_diff_assoc($payments, array_unique($payments));
        }

        array_push($payments, '1-0', '1-1', '2-0', '2-1');

        if (Agent::isDesktop()) {
            return view('bag.order-data', compact('bag_data', 'client', 'districts', 'payments', 'header_title'));
        } else {
            return view('mobile.bag.order-data', compact('bag_data', 'client', 'districts', 'payments', 'header_title'));
        }
    }

    public function finish(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules(),
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

         if ($validator->fails()) {
              $return['status'] = false;
              $return['msg'] = $validator->errors()->first();
        } else {
            $client_id = Auth::guard('client')->user()->id;

            // Search the city
            $city = City::whereHas('state', function ($query) use ($request) {
                    $query->where('letter', $request->state);
                })
                ->where('title', 'LIKE', '%' . $request->city . '%')
                ->select('id')
                ->first();

            if (!$city) {
                $return['status'] = false;
                $return['msg'] = 'Não identificamos a cidade informada. Verifique a cidade e o estado e tente novamente.';
            } else if ($city->id != 4913) {
                $return['status'] = false;
                $return['msg'] = 'Nossa entrega ainda não está disponível na sua região.';
            } else {
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

                    $return['status'] = true;
                    $return['route'] = route('bag-success', $order->id);
                }
            }
        }

        return json_encode($return);
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
        }

        $header_title = 'Pedido realizado - naslojas.com';

        if (Agent::isDesktop()) {
            return view('bag.success', compact('order', 'products', 'header_title'));
        } else {
            return view('mobile.bag.success', compact('order', 'products', 'header_title'));
        }
    }

    private function rules()
    {
        return [
            'cep' => 'required|max:10',
            'street' => 'required|max:200',
            'district' => 'required|max:100',
            'number' => 'required|max:15',
            'city' => 'required',
            'state' => 'required',
            'cpf' => 'required|max:15',
            'phone' => 'required|max:15',
            'payment' => 'required'
        ];
    }
}
