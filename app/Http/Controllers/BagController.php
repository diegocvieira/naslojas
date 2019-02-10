<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Store;
use App\Client;
use App\District;
use Session;
use Auth;

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

    public function data()
    {
        if (!Session::has('bag')) {
            return redirect()->route('home');
        }

        $today = time();

        $date1 = _businessDay();
        $week1 = date('w', strtotime($date1));
        $date1_formatted = date('d/m/Y', strtotime($date1));
        $week_text1 = _weekAbbreviation($week1);

        if (date('w') != 6 && date('w') != 0) {
            $date2 = _businessDay(date('Y-m-d', strtotime($date1 . ' + 1 day')));
            $week2 = date('w', strtotime($date2));
            $date2_formatted = date('d/m/Y', strtotime($date2));
            $week_text2 = _weekAbbreviation($week2);
        } else {
            $week2 = null;
        }
















        foreach (session('bag')['stores'] as $store_key => $s) {
            $store = Store::find($s['store_id']);

            $bag_data[$store_key]['freight'] = 0;
            $bag_data[$store_key]['store'] = $store->name;
            $bag_data[$store_key]['city'] = $store->city->title;
            $bag_data[$store_key]['state'] = $store->city->state->letter;
            $bag_data[$store_key]['street'] = $store->street;
            $bag_data[$store_key]['district'] = $store->district;
            $bag_data[$store_key]['number'] = $store->number;
            $bag_data[$store_key]['complement'] = $store->complement;













                foreach ($store->operatings as $operating_key => $operating) {
                    if ($operating->week == $week1 || $operating->week == $week2) {
                        // Add 30 minutes to opening hour
                        $opening_morning = date('H:i', strtotime($operating->opening_morning . ' + 30 minute'));
                        // Add 30 minutes to closed hour
                        $closed_afternoon = date('H:i', strtotime($operating->closed_afternoon . ' - 30 minute'));

                        $z = 0;
                        $i = 0;
                        $attempts = true;

                        if ($operating->closed_morning) {
                            // CALCULATE MORNING HOURS
                            do {
                                $generate_hour = date('H:i', strtotime($opening_morning . ' + ' . $i . ' hour'));
                                $generate_hour2 = date('H:i', strtotime($generate_hour . ' + 1 hour'));

                                if (strtotime($generate_hour) && strtotime($generate_hour) >= strtotime($opening_morning) && strtotime($generate_hour) <= strtotime($operating->closed_morning) && strtotime($generate_hour2) <= strtotime($operating->closed_morning)) {

                                    if (strtotime($today) < strtotime($opening_morning) || strtotime($today) >= strtotime($opening_morning) && strtotime($today) <= strtotime($generate_hour)) {
                                        $hours[$store_key][$operating_key][$z] = ($operating->week == $week1 ? ($week_text1 . ' ' . $date1_formatted) : ($week_text2 . ' ' . $date2_formatted)) . ' entre ' . $generate_hour . ' e ' . $generate_hour2;

                                        $z++;
                                    }

                                    $i++;
                                } else {
                                    $attempts = false;
                                }
                            } while ($attempts == true);

                            // CALCULATE AFTERNOON HOURS
                            $i = 0;
                            $attempts = true;

                            do {
                                $generate_hour = date('H:i', strtotime($operating->opening_afternoon . ' + ' . $i . ' hour'));
                                $generate_hour2 = date('H:i', strtotime($generate_hour . ' + 1 hour'));

                                if (strtotime($generate_hour) && strtotime($generate_hour) >= strtotime($operating->opening_afternoon) && strtotime($generate_hour) <= strtotime($closed_afternoon) && strtotime($generate_hour2) <= strtotime($closed_afternoon)) {
                                    if (strtotime($today) < strtotime($operating->opening_afternoon) || strtotime($today) >= strtotime($operating->opening_afternoon) && strtotime($today) <= strtotime($generate_hour)) {
                                        $hours[$store_key][$operating_key][$z] = ($operating->week == $week1 ? ($week_text1 . ' ' . $date1_formatted) : ($week_text2 . ' ' . $date2_formatted)) . ' entre ' . $generate_hour . ' e ' . $generate_hour2;

                                        $z++;
                                    }

                                    $i++;
                                } else {
                                    $attempts = false;
                                }
                            } while ($attempts == true);
                        } else {
                            // CALCULATE MORNING TO AFTERNOON HOUR
                            do {
                                $generate_hour = date('H:i', strtotime($opening_morning . ' + ' . $i . ' hour'));
                                $generate_hour2 = date('H:i', strtotime($generate_hour . ' + 1 hour'));

                                if (strtotime($generate_hour) && strtotime($generate_hour) >= strtotime($opening_morning) && strtotime($generate_hour) <= strtotime($closed_afternoon) && strtotime($generate_hour2) <= strtotime($closed_afternoon)) {
                                    if (strtotime($today) < strtotime($opening_morning) || strtotime($today) >= strtotime($opening_morning) && strtotime($today) <= strtotime($generate_hour)) {
                                        $hours[$store_key][$operating_key][$z] = ($operating->week == $week1 ? ($week_text1 . ' ' . $date1_formatted) : ($week_text2 . ' ' . $date2_formatted)) . ' entre ' . $generate_hour . ' e ' . $generate_hour2;

                                        $z++;
                                    }

                                    $i++;
                                } else {
                                    $attempts = false;
                                }
                            } while ($attempts == true);
                        }
                    }
                }













            $bag_data[$store_key]['subtotal'] = 0;
            foreach ($s['products'] as $p) {
                $product = Product::select('price', 'store_id')->find($p['id']);

                $bag_data[$store_key]['subtotal'] += $product->price * $p['qtd'];
            }
        }










        if (count($hours) > 1) {
            // Get the first and last hour
            foreach ($hours as $hour) {
                foreach ($hour as $week) {
                    $first_value[] = substr(reset($week), 23, 5);
                    $last_value[] = substr(end($week), 23, 5);
                }
            }

            $min = min($first_value);
            $max = max($last_value);

            // Remove de min and max value
            foreach ($hours as $key => $hour) {
                foreach ($hour as $key2 => $week) {
                    foreach($week as $key3 => $time) {
                        if (substr($time, 23, 5) == $min || substr($time, 23, 5) == $max) {
                            unset($hours[$key][$key2][$key3]);
                        }
                    }
                }
            }
        }

        foreach ($hours as $key => $hour) {
            foreach ($hour as $key2 => $week) {
                foreach($week as $key3 => $time) {
                    $reserve_hours[$hours[$key][$key2][$key3]] = $hours[$key][$key2][$key3];
                }
            }
        }


        //return $reserve_hours;








        $client = Client::find(Auth::guard('client')->user()->id);

        $districts = District::pluck('name', 'id');

        $header_title = 'Dados do pedido | naslojas.com';

        return view('bag.order-data', compact('bag_data', 'client', 'districts', 'reserve_hours', 'header_title'));
    }
}
