<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product;
use Auth;
use App\User;
use Validator;
use Agent;
use Mail;
use App\ProductSize;
use App\Models\Token;
use DB;

class StoreController extends Controller
{
    public function show($slug)
    {
        $store = Store::where('slug', $slug)
            ->isActive()
            // ->clientDistrict()
            ->firstOrFail();

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
            } else {
                $section = null;
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
        $store = Store::where('slug', $store_slug)
            ->isActive()
            // ->clientDistrict()
            ->firstOrFail();

        $search_gender = $request->gender ?? null;
        $search_order = $request->order ?? null;
        $keyword = $request->keyword ?? null;
        // $advanced = $request->advanced ?? null;
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

            // if ($advanced == 'true') {
            //     $products = $products->where(function ($query) use ($keyword) {
            //         $query->search($keyword);
            //     });

            //     if ($keyword == 'estilo') {
            //         $terms = ['sapato', 'calcado', 'salto alto', 'sapatenis', 'casual', 'colete', 'scarpin', 'jeans', 'sapatilha', 'sandalia', 'calca jeans', 'peep toe', 'bota', 'saia', 'mini saia', 'short', 'bermuda', 'calca', 'vestido', 'blusa', 'camisa', 'camiseta', 'casaco', 'jaqueta', 'blusao', 'moletom', 'moleton', 'agasalho', 'blusinha', 'sobretudo', 'mala', 'mochila', 'bolsa', 'joia', 'relogio', 'anel', 'chapeu', 'manta', 'maleta', 'carteira', 'bikini', 'biquini', 'luva', 'meia', 'carpim', 'bone', 'tiara', 'brinco', 'pochete', 'colar', 'pulseira', 'oculos', 'oculos de sol', 'oculos escuros', 'maquiagem', 'batom', 'tornozeleira', 'cinto', 'suspensorio'];
            //     } else if ($keyword == 'esporte') {
            //         $terms = ['nike', 'adidas', 'olympikus', 'mizuno', 'asics', 'bola', 'esporte', 'gremio', 'inter', 'time', 'penalty', 'topper', 'futebol', 'tenis adidas', 'tenis nike', 'tenis olympikus', 'tenis mizuno', 'tenis asics', 'tenis topper', 'tenis penalty', 'tenis corrida', 'tenis basket', 'tenis academia', 'basquete', 'basket', 'volei', 'corrida', 'academia', 'treino', 'regata', 'camiseta regata', 'calcao', 'meiao', 'sunga', 'maio', 'caneleira', 'joelheira', 'cotoveleira'];
            //     } else if ($keyword == 'casual') {
            //         $terms = ['sapato', 'calcado', 'salto alto', 'sapatenis', 'casual', 'colete', 'scarpin', 'jeans', 'sapatilha', 'sandalia', 'calca jeans', 'peep toe', 'bota', 'saia', 'mini saia', 'short', 'bermuda', 'calca', 'vestido', 'blusa', 'camisa', 'camiseta', 'casaco', 'jaqueta', 'blusao', 'moletom', 'moleton', 'agasalho', 'blusinha', 'sobretudo'];
            //     } else if ($keyword == 'acessorios') {
            //         $terms = ['mala', 'mochila', 'bolsa', 'joia', 'relogio', 'anel', 'chapeu', 'manta', 'maleta', 'carteira', 'bikini', 'biquini', 'luva', 'meia', 'carpim', 'bone', 'tiara', 'brinco', 'pochete', 'colar', 'pulseira', 'oculos', 'oculos de sol', 'oculos escuros', 'maquiagem', 'batom', 'tornozeleira', 'cinto', 'suspensorio'];
            //     }

            //     foreach ($terms as $t) {
            //         $products = $products->orWhere(function ($q) use ($t) {
            //             $q->search($t);
            //         });
            //     }
            // } else {
                $products = $products->where(function ($query) use ($keyword) {
                    $query->search($keyword)
                        ->orWhereHas('store', function ($query) use ($keyword) {
                            $query->search($keyword);
                        });
                });
            // }
        } else {
            $header_title = null;
            $header_desc = null;
        }

        $products = $products->paginate(32);

        // if ($keyword && $products->count() == 0) {
        //     $products = Product::has('images')
        //         ->where('store_id', $store->id)
        //         ->filterGender($search_gender)
        //         ->filterOrder($search_order)
        //         ->filterMinPrice($search_min_price)
        //         ->filterMaxPrice($search_max_price)
        //         ->filterSize($search_size)
        //         ->filterOff($search_off)
        //         ->filterInstallment($search_installment)
        //         ->filterBrand($search_brand)
        //         ->filterFreight($search_freight)
        //         ->filterCategory($search_category)
        //         ->filterColor($search_color)
        //         ->where(function ($query) use ($keyword) {
        //             $query->search(preg_replace('{(.)\1+}','$1', $keyword))->orWhereHas('store', function ($query) use ($keyword) {
        //                 $query->search(preg_replace('{(.)\1+}','$1', $keyword));
        //             });
        //         })
        //         ->paginate(30);
        // }

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
            $request->all(), [
                'email' => 'required|email|max:100|unique:users',
                'name' => 'required|max:200',
                'password' => 'confirmed|min:8',
                'token' => 'required|exists:tokens,token'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'msg' => $validator->errors()->first(),
                'status' => false
            ]);
        }

        try {
            DB::beginTransaction();

            $store = new Store;
            $store->name = $request->name;
            $store->slug = str_slug($store->name, '-');

            // check if slug already exists and add dash in the end
            $attemptsMax = 10;
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
            } while ($attempts < $attemptsMax);

            $user = new User;
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->store_id = $store->id;
            $user->save();

            Token::where('token', $request->token)->first()->delete();

            DB::commit();

            $this->login($request);

            session()->flash('session_flash_alert', 'Cadastro realizado com sucesso! <br> Complete as informações e ative o perfil da loja.');
            $return['status'] = true;
            $return['url'] = route('get-store-config');
        } catch (\Throwable $th) {
            DB::rollBack();

            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
        }

        return response()->json($return);
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
}
