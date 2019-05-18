<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product;
use Auth;
use App\User;
use Validator;
use Hash;
use App\City;
use Agent;
use Mail;
use App\District;
use App\ProductSize;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('store')->check()) {
                $this->store_id = Auth::guard('store')->user()->store_id;
                $this->user_id = Auth::guard('store')->user()->id;
            } else if (Auth::guard('superadmin')->check()) {
                $this->store_id = session('superadmin_store_id');
                $this->user_id = session('superadmin_user_id');
            }

            return $next($request);
        });
    }

    public function show($slug)
    {
        $store = Store::where('slug', $slug)->where('status', 1)->firstOrFail();

        // SEO
        $header_title = $store->name . ' - ' . $store->city->title . ' / ' . $store->city->state->letter . ' | naslojas';
		$header_desc = 'Clique para ver os produtos disponíveis na loja ' . $store->name . ' em ' . $store->city->title . ' - ' . $store->city->state->letter;

        $products = Product::where('store_id', $store->id)
            ->has('images')
            ->inRandomOrder()
            ->paginate(32);

        // FILTERS //

        $genders = _filterGender();
		foreach ($genders as $key => $gender) {
			$p = Product::where('store_id', $store->id)->filterGender($gender)->select('id')->first();

			if (!$p) {
				unset($genders[$key]);
			}
		}

		$offs = _filterOff();
		foreach ($offs as $key => $off) {
			$p = Product::where('store_id', $store->id)->filterOff($key)->select('id')->first();

			if (!$p) {
				unset($offs[$key]);
			}
		}

		$installments = _filterInstallment();
		foreach ($installments as $key => $installment) {
			$p = Product::where('store_id', $store->id)->filterInstallment($key)->select('id')->first();

			if (!$p) {
				unset($installments[$key]);
			}
		}

        $colors = _filterColor();
		foreach ($colors as $key => $color) {
			$p = Product::where('store_id', $store->id)->filterColor($color)->select('id')->first();

			if (!$p) {
				unset($colors[$key]);
			}
		}

		$brands = _filterBrand();
		foreach ($brands as $key => $brand) {
			$p = Product::where('store_id', $store->id)->filterBrand($brand)->select('id')->first();

			if (!$p) {
				unset($brands[$key]);
			}
		}

		$categories = _filterCategory();
		foreach ($categories as $key => $category) {
			$p = Product::where('store_id', $store->id)->filterCategory($category)->select('id')->first();

			if (!$p) {
				unset($categories[$key]);
			}
		}

        $prices = _filterPrice();
		/*foreach ($prices as $key => $price) {
			$p = Product::where('title', 'LIKE', '%' . $brand . '%')->select('id')->first();

			if (!$p) {
				unset($brands[$key]);
			}
		}*/

        $sizes = ProductSize::whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })
            ->select('size')
			->distinct()
			->orderBy('size', 'ASC')
			->get();

        $orderby = _filterOrder();

        if (Agent::isDesktop()) {
            return view('store.show', compact('store', 'products', 'orderby', 'prices', 'genders', 'offs', 'sizes', 'installments', 'colors', 'categories', 'brands', 'header_title', 'header_desc'));
        } else {
            if (Auth::guard('store')->check() && $store->id == Auth::guard('store')->user()->store_id) {
                $section = 'store';
            }

            return view('mobile.store.show', compact('store', 'products', 'section', 'orderby', 'prices', 'genders', 'offs', 'sizes', 'installments', 'colors', 'categories', 'brands', 'header_title', 'header_desc'));
        }
    }

    public function formSearch(Request $request)
    {
        $store_slug = $request->store_slug;
        $gender = $request->gender;
        $order = $request->order;
        $keyword = urlencode($request->keyword);

        if ($keyword && !$order) {
            $order = 'palavra-chave';
        }

        return redirect()->action('StoreController@search', [$store_slug, $gender, $order, $keyword]);
    }

    public function search($store_slug, Request $request)
    {
        $store = Store::where('slug', $store_slug)->firstOrFail();

        $search_gender = $request->gender ?? null;
        $search_order = $request->order ?? null;
        $keyword = $request->keyword ?? null;
        $advanced = $request->advanced ?? null;
        $search_max_price = $request->max_price ?? null;
        $search_min_price = $request->min_price ?? null;
        $search_size = $request->size ?? null;
        $search_off = $request->off ?? null;
        $search_installment = $request->installment ?? null;
        $search_brand = $request->brand ?? null;
        $search_freight = $request->freight ?? null;
        $search_category = $request->category ?? null;
        $search_color = $request->color ?? null;

        $products = Product::has('images')
            ->where('store_id', $store->id)
            ->filterGender($search_gender)
            ->filterOrder($search_order)
            ->filterMinPrice($search_min_price)
            ->filterMaxPrice($search_max_price)
            ->filterSize($search_size)
            ->filterOff($search_off)
            ->filterInstallment($search_installment)
            ->filterBrand($search_brand)
            ->filterFreight($search_freight)
            ->filterCategory($search_category)
            ->filterColor($search_color);

        if ($keyword) {
            //$keyword = urldecode($keyword);

            // SEO
            $header_title = $keyword . ' em ' . $store->name . ' - ' . $store->city->title . ' / ' . $store->city->state->letter . ' - naslojas.com';
			$header_desc = 'Clique para ver ' . $keyword . ' na loja ' . $store->name . ' em ' . $store->city->title . ' - ' . $store->city->state->letter;

            if ($advanced == 'true') {
                $products = $products->where(function ($query) use ($keyword) {
                    $query->search($keyword);
                });

                if ($keyword == 'estilo') {
                    $terms = ['sapato', 'calcado', 'salto alto', 'sapatenis', 'casual', 'colete', 'scarpin', 'jeans', 'sapatilha', 'sandalia', 'calca jeans', 'peep toe', 'bota', 'saia', 'mini saia', 'short', 'bermuda', 'calca', 'vestido', 'blusa', 'camisa', 'camiseta', 'casaco', 'jaqueta', 'blusao', 'moletom', 'moleton', 'agasalho', 'blusinha', 'sobretudo', 'mala', 'mochila', 'bolsa', 'joia', 'relogio', 'anel', 'chapeu', 'manta', 'maleta', 'carteira', 'bikini', 'biquini', 'luva', 'meia', 'carpim', 'bone', 'tiara', 'brinco', 'pochete', 'colar', 'pulseira', 'oculos', 'oculos de sol', 'oculos escuros', 'maquiagem', 'batom', 'tornozeleira', 'cinto', 'suspensorio'];
                } else if ($keyword == 'esporte') {
                    $terms = ['nike', 'adidas', 'olympikus', 'mizuno', 'asics', 'bola', 'esporte', 'gremio', 'inter', 'time', 'penalty', 'topper', 'futebol', 'tenis adidas', 'tenis nike', 'tenis olympikus', 'tenis mizuno', 'tenis asics', 'tenis topper', 'tenis penalty', 'tenis corrida', 'tenis basket', 'tenis academia', 'basquete', 'basket', 'volei', 'corrida', 'academia', 'treino', 'regata', 'camiseta regata', 'calcao', 'meiao', 'sunga', 'maio', 'caneleira', 'joelheira', 'cotoveleira'];
                } else if ($keyword == 'casual') {
                    $terms = ['sapato', 'calcado', 'salto alto', 'sapatenis', 'casual', 'colete', 'scarpin', 'jeans', 'sapatilha', 'sandalia', 'calca jeans', 'peep toe', 'bota', 'saia', 'mini saia', 'short', 'bermuda', 'calca', 'vestido', 'blusa', 'camisa', 'camiseta', 'casaco', 'jaqueta', 'blusao', 'moletom', 'moleton', 'agasalho', 'blusinha', 'sobretudo'];
                } else if ($keyword == 'acessorios') {
                    $terms = ['mala', 'mochila', 'bolsa', 'joia', 'relogio', 'anel', 'chapeu', 'manta', 'maleta', 'carteira', 'bikini', 'biquini', 'luva', 'meia', 'carpim', 'bone', 'tiara', 'brinco', 'pochete', 'colar', 'pulseira', 'oculos', 'oculos de sol', 'oculos escuros', 'maquiagem', 'batom', 'tornozeleira', 'cinto', 'suspensorio'];
                }

                foreach ($terms as $t) {
                    $products = $products->orWhere(function ($q) use ($t) {
                        $q->search($t);
                    });
                }
            } else {
                $products = $products->where(function ($query) use ($keyword) {
                    $query->search($keyword)
                        ->orWhereHas('store', function ($query) use ($keyword) {
                            $query->search($keyword);
                        });
                });
            }
        }

        $products = $products->paginate(32);

        if ($keyword && $products->count() == 0) {
            $products = Product::has('images')
                ->filterGender($search_gender)
                ->filterOrder($search_order)
                ->filterMinPrice($search_min_price)
                ->filterMaxPrice($search_max_price)
                ->filterSize($search_size)
                ->filterOff($search_off)
                ->filterInstallment($search_installment)
                ->filterBrand($search_brand)
                ->filterFreight($search_freight)
                ->filterCategory($search_category)
                ->filterColor($search_color)
                ->where(function ($query) use ($keyword) {
                    $query->search(preg_replace('{(.)\1+}','$1', $keyword))->orWhereHas('store', function ($query) use ($keyword) {
                        $query->search(preg_replace('{(.)\1+}','$1', $keyword));
                    });
                })
                ->paginate(30);
        }

        // FILTERS //

        $genders = _filterGender();
		foreach ($genders as $key => $gender) {
			$p = Product::where('store_id', $store->id)->filterGender($gender)->select('id')->first();

			if (!$p) {
				unset($genders[$key]);
			}
		}

		$offs = _filterOff();
		foreach ($offs as $key => $off) {
			$p = Product::where('store_id', $store->id)->filterOff($key)->select('id')->first();

			if (!$p) {
				unset($offs[$key]);
			}
		}

		$installments = _filterInstallment();
		foreach ($installments as $key => $installment) {
			$p = Product::where('store_id', $store->id)->filterInstallment($key)->select('id')->first();

			if (!$p) {
				unset($installments[$key]);
			}
		}

        $colors = _filterColor();
		foreach ($colors as $key => $color) {
			$p = Product::where('store_id', $store->id)->filterColor($color)->select('id')->first();

			if (!$p) {
				unset($colors[$key]);
			}
		}

		$brands = _filterBrand();
		foreach ($brands as $key => $brand) {
			$p = Product::where('store_id', $store->id)->filterBrand($brand)->select('id')->first();

			if (!$p) {
				unset($brands[$key]);
			}
		}

		$categories = _filterCategory();
		foreach ($categories as $key => $category) {
			$p = Product::where('store_id', $store->id)->filterCategory($category)->select('id')->first();

			if (!$p) {
				unset($categories[$key]);
			}
		}

        $prices = _filterPrice();
		/*foreach ($prices as $key => $price) {
			$p = Product::where('title', 'LIKE', '%' . $brand . '%')->select('id')->first();

			if (!$p) {
				unset($brands[$key]);
			}
		}*/

        $sizes = ProductSize::whereHas('product', function ($q) use ($store) {
                $q->where('store_id', $store->id);
            })
            ->select('size')
			->distinct()
			->orderBy('size', 'ASC')
			->get();

        $orderby = _filterOrder();

        if (Agent::isDesktop()) {
            return view('store.show', compact('products', 'store', 'keyword', 'orderby', 'genders', 'prices', 'sizes', 'offs', 'installments', 'colors', 'categories', 'brands', 'search_color', 'search_category', 'search_freight', 'search_brand', 'search_installment', 'search_gender', 'search_off', 'search_size', 'search_order', 'search_min_price', 'search_max_price', 'header_title', 'header_desc'));
        } else {
            return view('mobile.store.show', compact('products', 'store', 'keyword', 'orderby', 'genders', 'prices', 'sizes', 'offs', 'installments', 'colors', 'categories', 'brands', 'search_color', 'search_category', 'search_freight', 'search_brand', 'search_installment', 'search_gender', 'search_off', 'search_size', 'search_order', 'search_min_price', 'search_max_price', 'header_title', 'header_desc'));
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:100|unique:users',
                'name' => 'required|max:200',
                'password' => 'confirmed|min:8'
            ],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if ($validator->fails()) {
            $return['msg'] = $validator->errors()->first();
            $return['status'] = false;
        } else {
            $store = new Store;
            $store->name = $request->name;
            $store->slug = str_slug($store->name, '-');

            // check if slug already exists and add dash in the end
            $NUM_OF_ATTEMPTS = 10;
            $attempts = 0;

            do {
                try {
                    $store->save();
                } catch(\Exception $e) {
                    $attempts++;

                    sleep(rand(0, 10) / 10);

                    $store->slug .= '-' . uniqid();

                    continue;
                }

                break;
            } while ($attempts < $NUM_OF_ATTEMPTS);

            $user = new User;
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->store_id = $store->id;
            $user->save();

            // Create the folder if not exists (necessary to upload images)
            $path = public_path('uploads/' . $store->id . '/products');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            session()->flash('session_flash_alert', 'Cadastro realizado com sucesso! <br> Complete as informações e ative o perfil da loja.');

            return $this->login($request);
        }

        return json_encode($return);

        /*Mail::send('emails.store-register', ['request' => $request], function($m) {
            $m->from('no-reply@naslojas.com', 'naslojas');
            $m->to('contato@naslojas.com');
            $m->subject('Solicitação de cadastro');
        });

        if (!Mail::failures()) {
            $return['msg'] = 'Solicitação de cadastro enviada com sucesso!';
        } else {
            $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente mais tarde.';
        }

        return json_encode($return);*/
    }

    public function login(Request $request)
    {
        if (Auth::guard('store')->attempt(['email' => $request->email, 'password' => $request->password], true)) {
            $return['status'] = true;
            $return['url'] = Auth::guard('store')->user()->store->status ? route('show-store', Auth::guard('store')->user()->store->slug) : route('get-store-config');
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não identificamos o e-mail e/ou a senha que você informou.';
        }

        return json_encode($return);
    }

    public function getConfig($navigation = null)
    {
        $section = 'config';
        $header_title = 'Configurações | naslojas.com';

        $user = User::find($this->user_id);
        $districts = District::orderBy('name', 'ASC')->get();

        /*$weeks = [
            '1' => 'Segunda',
            '2' => 'Terça',
            '3' => 'Quarta',
            '4' => 'Quinta',
            '5' => 'Sexta',
            '6' => 'Sábado'
        ];*/

        $payments = [];

        foreach ($user->store->payments as $payment) {
            $payments[] = $payment->method . '-' . $payment->payment;
        }

        array_push($payments, '0-0', '1-0', '1-1', '2-0', '2-1');

        if (Agent::isDesktop()) {
            return view('store.config', compact('user', 'districts', 'payments', 'section', 'header_title', 'navigation'));
        } else {
            return view('mobile.store.config', compact('user', 'districts', 'payments', 'section', 'header_title', 'navigation'));
        }
    }

    public function setConfig(Request $request)
    {
        $section = $request->section;

        if ($section == 'store-profile') {
            $rules = $this->storeProfileRules();
        } else if ($section == 'address') {
            $rules = $this->addressRules();
        } else if ($section == 'access') {
            $rules = $this->accessRules();
        } else if ($section == 'payment') {
            $rules = $this->paymentRules();
        } else {
            $rules = [];
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

         if ($validator->fails()) {
             $return['msg'] = $validator->errors()->first();
             $return['status'] = 0;
        } else {
            $user = User::find($this->user_id);
            $store = Store::find($user->store_id);

            $current_password = Auth::guard('superadmin')->check() ? Auth::guard('superadmin')->user()->password : $user->password;

            if (Hash::check($request->current_password, $current_password) || Auth::guard('superadmin')->check()) {
                if ($section == 'address') {
                    // Search the city
                    $city = City::whereHas('state', function ($query) use ($request) {
                        $query->where('letter', $request->state);
                    })->where('title', 'LIKE', '%' . $request->city . '%')->select('id')->first();

                    if (!$city) {
                        $return['msg'] = 'Não identificamos a cidade informada.';
                        $return['status'] = 0;

                        return json_encode($return);
                    } else if ($city->id != 4913) {
                        $return['msg'] = 'Em breve estaremos trabalhando na sua cidade.';
                        $return['status'] = 0;

                        return json_encode($return);
                    }

                    if (isset($city)) {
                        $store->city_id = $city->id;
                    }

                    $store->cep = $request->cep;
                    $store->street = $request->street;
                    $store->number = $request->number;
                    $store->complement = $request->complement;
                    $store->district = $request->district;
                } else if ($section == 'store-profile') {
                    $store->name = $request->name;
                    $store->slug = str_slug($request->slug, '-');
                    $store->cnpj = $request->cnpj;
                    $store->phone = $request->phone;

                    if ($request->delete_image_cover_desktop && $store->image_cover_desktop || $request->image_cover_desktop && $store->image_cover_desktop) {
                        $image_path = public_path('uploads/' . $this->store_id . '/' . $store->image_cover_desktop);

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }

                        $store->image_cover_desktop = null;
                    }

                    if ($request->image_cover_desktop) {
                        $store->image_cover_desktop = _uploadImage($request->image_cover_desktop, $this->store_id);
                    }

                    if ($request->delete_image_cover_mobile && $store->image_cover_mobile || $request->image_cover_mobile && $store->image_cover_mobile) {
                        $image_path = public_path('uploads/' . $this->store_id . '/' . $store->image_cover_mobile);

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }

                        $store->image_cover_mobile = null;
                    }

                    if ($request->image_cover_mobile) {
                        $store->image_cover_mobile = _uploadImage($request->image_cover_mobile, $this->store_id);
                    }
                } else if ($section == 'access') {
                    $user->email = $request->email;

                    if ($request->password) {
                        $user->password = bcrypt($request->password);
                    }
                } else if ($section == 'payment') {
                    $store->max_product_unit = $request->max_product_unit;
                    $store->max_parcel = $request->max_parcel;
                    $store->min_parcel_price = $request->min_parcel_price ? number_format(str_replace(['.', ','], ['', '.'], $request->min_parcel_price), 2, '.', '') : null;
                    $store->free_freight_price = $request->free_freight_price ? number_format(str_replace(['.', ','], ['', '.'], $request->free_freight_price), 2, '.', '') : null;

                    if ($store->free_freight_price) {
                        Product::withoutGlobalScopes(['active', 'active-store'])
                            ->where('price', '>=', $store->free_freight_price)
                            ->update(['free_freight' => 1]);
                        Product::withoutGlobalScopes(['active', 'active-store'])
                            ->where('price', '<', $store->free_freight_price)
                            ->update(['free_freight' => 0]);
                    }

                    // Delete and insert new payments
                    $store->payments()->delete();
                    if ($request->payment) {
                        foreach ($request->payment as $payment) {
                            $payment_split = explode('-', $payment);

                            $store->payments()->create([
                                'method' => $payment_split[0],
                                'payment' => $payment_split[1]
                            ]);
                        }
                    }
                } else if ($section == 'freights') {
                    // Delete and insert new freights
                    $freights = array_map(function($q, $t) {
                        return array('id' => $q, 'price' => $t);
                    }, $request->district_id, $request->freight_price);

                    $store->freights()->delete();

                    foreach ($freights as $freight) {
                        if ($freight['price']) {
                            $store->freights()->create([
                                'price' => number_format(str_replace(array(".", ","), array("", "."), $freight['price']), 2, '.', ''),
                                'district_id' => $freight['id']
                            ]);
                        }
                    }
                }

                // Delete and insert new operation hours
                /*$hours = array_map(function($q, $t) {
                    return array('week' => $q, 'hour' => $t);
                }, $request->week_id, $request->operating);

                $store->operatings()->delete();

                foreach ($hours as $hour) {
                    if ($hour['hour']) {
                        if (strlen($hour['hour']) == 15) {
                            $opening_morning = substr($hour['hour'], 0, 5);
                            $closed_morning = null;
                            $opening_afternoon = null;
                            $closed_afternoon = substr($hour['hour'], 10, 5);
                        }

                        if (strlen($hour['hour']) == 33) {
                            $opening_morning = substr($hour['hour'], 0, 5);
                            $closed_morning = substr($hour['hour'], 10, 5);
                            $opening_afternoon = substr($hour['hour'], 18, 5);
                            $closed_afternoon = substr($hour['hour'], 28, 5);
                        }

                        $store->operatings()->create([
                            'week' => $hour['week'],
                            'opening_morning' => $opening_morning,
                            'closed_morning' => $closed_morning,
                            'opening_afternoon' => $opening_afternoon,
                            'closed_afternoon' => $closed_afternoon
                        ]);
                    }
                }*/

                if ($store->save() && $user->save()) {
                    if (Auth::guard('superadmin')->check()) {
                        app('App\Http\Controllers\SuperadminController')->setStore($store->id);
                    }

                    $return['msg'] = 'Informações atualizadas.';
                    $return['status'] = 1;
                } else {
                    $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
                    $return['status'] = 0;
                }
            } else {
                $return['msg'] = 'A sua senha atual não confere.';
                $return['status'] = 2;
            }
        }

        return json_encode($return);
    }

    public function profileStatus($status)
    {
        $store = Store::find($this->store_id);

        $districts_count = District::count();

        if ($status == 0 || $status == 1 && $store->freights->count() == $districts_count && $store->phone && $store->cnpj && $store->max_product_unit && $store->max_parcel && $store->min_parcel_price && $store->cep && $store->district && $store->street && $store->number) {
            $store->status = $status;

            $store->save();

            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function deleteAccount(Request $request)
    {
        $user = User::find($this->user_id);

        if (Hash::check($request->password, $user->password)) {
            Store::find($this->store_id)->delete();

            app('App\Http\Controllers\GlobalController')->logout();

            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function tutorials($type)
    {
        $header_title = 'Tutoriais | naslojas';

        $section = 'tutorial';

        return view('store.tutorials', compact('header_title', 'type', 'section'));
    }

    private function storeProfileRules()
    {
        return [
            'slug' => 'required|max:200|unique:stores,slug,' . $this->store_id,
            'name' => 'required|max:200',
            'phone' => 'required|max:15',
            'cnpj' => 'required|max:18'
        ];
    }

    private function paymentRules()
    {
        return [
            'max_product_unit' => 'numeric',
            'max_parcel' => 'numeric',
            'min_parcel_price' => 'required'
        ];
    }

    private function addressRules()
    {
        return [
            'cep' => 'required|max:10',
            'street' => 'required|max:200',
            'district' => 'required|max:100',
            'number' => 'required|max:15',
            'city' => 'required',
            'state' => 'required'
        ];
    }

    private function accessRules()
    {
        return [
            'email' => 'required|max:100|unique:users,email,' . $this->user_id,
            'password' => 'confirmed'
        ];
    }
}
