<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Session;

class BagController extends Controller
{
    public function add(Request $request)
    {
        $product_id = $request->product_id;
        $store_id = $request->store_id;
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

    public function products()
    {
        if (Session::has('bag')) {
            foreach (session('bag')['stores'] as $store) {
                foreach ($store['products'] as $product) {
                    $ids[] = $product['id'];
                }
            }

            $products = Product::find($ids);
        }

        if (\Request::ajax()) {
            return response()->json([
                'body' => view('bag.preview', compact('products'))->render()
            ]);
        } else {
            for ($i = 1; $i <= 5; $i++) {
                $qtd[$i] = $i;
            }

            $header_title = 'Itens na sacola | naslojas.com';

            return view('bag.products', compact('products', 'qtd', 'header_title'));
        }
    }
}
