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

class StoreController extends Controller
{
    public function show($slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();

        // SEO
        $header_title = $store->name . ' - ' . $store->city->title . ' / ' . $store->city->state->letter . ' | naslojas.com';
		$header_desc = 'Clique para ver os produtos disponíveis na loja ' . $store->name . ' em ' . $store->city->title . ' - ' . $store->city->state->letter;

        $products = Product::where('store_id', $store->id)->paginate(30);

        if (Agent::isDesktop()) {
            return view('store.show', compact('store', 'products', 'header_title', 'header_desc'));
        } else {
            return view('mobile.store.show', compact('store', 'products', 'header_title', 'header_desc'));
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

    public function search($store_slug, $search_gender, $search_order = null, $keyword = null)
    {
        $store = Store::where('slug', $store_slug)->firstOrFail();

        $products = Product::where('store_id', $store->id)->filterGender($search_gender)->filterOrder($search_order);

        if ($keyword) {
            $keyword = urldecode($keyword);

            // SEO
            $header_title = $keyword . ' em ' . $store->name . ' - ' . $store->city->title . ' / ' . $store->city->state->letter . ' - naslojas.com';
			$header_desc = 'Clique para ver ' . $keyword . ' na loja ' . $store->name . ' em ' . $store->city->title . ' - ' . $store->city->state->letter;

            $products = $products->where(function ($query) use ($keyword) {
                $query->search($keyword);
            });
        }

        $products = $products->paginate(30);

        if ($keyword && $products->count() == 0) {
            $products = Product::where('store_id', $store->id)->filterGender($search_gender)->filterOrder($search_order)
                ->where(function ($query) use ($keyword) {
                    $query->search(preg_replace('{(.)\1+}','$1', $keyword));
                })
                ->paginate(30);
        }

        if (Agent::isDesktop()) {
            return view('store.show', compact('products', 'store', 'keyword', 'search_gender', 'search_order', 'header_title', 'header_desc'));
        } else {
            return view('mobile.store.show', compact('products', 'store', 'keyword', 'search_gender', 'search_order', 'header_title', 'header_desc'));
        }
    }

    public function register(Request $request)
    {
        /*$validator = Validator::make(
            $request->all(),
            ['email' => 'required|email|max:100|unique:users', 'password' => 'confirmed|min:8'],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if($validator->fails()) {
            $return['msg'] = $validator->errors()->first();
            $return['status'] = false;
        } else {
            $store = new Store;
            $store->save();

            $user = new User;
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->store_id = $store->id;

            if($user->save()) {
                // Create the folder if not exists (necessary to uploads images)
                $path = public_path('uploads/' . $store->id . '/products');
                if(!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                session()->flash('session_flash_alert', 'Cadastro realizado com sucesso! <br> Acesse as configurações para ativar o perfil da loja e finalizar o cadastro.');

                return $this->login($request);
            } else {
                $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
                $return['status'] = false;
            }
        }*/

        Mail::send('emails.store-register', ['request' => $request], function($m) {
            $m->from('no-reply@naslojas.com', 'naslojas');
            $m->to('contato@naslojas.com');
            $m->subject('Solicitação de cadastro');
        });

        if(!Mail::failures()) {
            $return['msg'] = 'Solicitação de cadastro enviada com sucesso!';
        } else {
            $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente mais tarde.';
        }

        return json_encode($return);
    }

    public function login(Request $request)
    {
        if (Auth::guard('store')->attempt(['email' => $request->email, 'password' => $request->password], true)) {
            $return['status'] = true;
            $return['url'] = '/' . (Auth::guard('store')->user()->store->status ? Auth::guard('store')->user()->store->slug : 'loja/admin/produtos/cadastro');
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não identificamos o e-mail e/ou a senha que você informou.';
        }

        return json_encode($return);
    }

    public function getConfig()
    {
        $user = User::find(Auth::guard('store')->user()->id);

        if (Agent::isDesktop()) {
            return response()->json([
                'body' => view('store.config', compact('user'))->render()
            ]);
        } else {
            return view('mobile.store.config', compact('user'));
        }
    }

    public function setConfig(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->storeEditRules(),
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

         if ($validator->fails()) {
             $return['msg'] = $validator->errors()->first();
             $return['status'] = 0;
        } else {
            if (Hash::check($request->current_password, Auth::guard('store')->user()->password)) {
                // Search the city
                $city = City::whereHas('state', function ($query) use ($request) {
                    $query->where('letter', $request->state);
                })->where('title', 'LIKE', '%' . $request->city . '%')->select('id')->first();

                if (!$city) {
                    $return['msg'] = 'Não identificamos a cidade informada.';
                    $return['status'] = 0;
                } else if ($city->id != 4913) {
                    $return['msg'] = 'Em breve estaremos trabalhando na sua cidade.';
                    $return['status'] = 0;
                } else {
                    $user = User::find(Auth::guard('store')->user()->id);
                    $user->email = $request->email;

                    if ($request->password) {
                        $user->password = bcrypt($request->password);
                    }

                    $store = Store::find($user->store_id);
                    $store->city_id = $city->id;
                    $store->name = $request->name;
                    $store->slug = str_slug($request->slug, '-');
                    $store->cep = $request->cep;
                    $store->street = $request->street;
                    $store->number = $request->number;
                    $store->complement = $request->complement;
                    $store->district = $request->district;
                    $store->status = isset($request->status) ? 1 : 0;
                    $store->reserve = isset($request->reserve) ? 1 : 0;

                    if ($store->save() && $user->save()) {
                        $return['msg'] = 'Informações atualizadas.';
                        $return['status'] = 1;
                    } else {
                        $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
                        $return['status'] = 0;
                    }
                }
            } else {
                $return['msg'] = 'A sua senha atual não confere.';
                $return['status'] = 2;
            }
        }

        return json_encode($return);
    }

    public function deleteAccount(Request $request)
    {
        if(Hash::check($request->password, Auth::guard('store')->user()->password)) {
            Store::find(Auth::guard('store')->user()->store_id)->delete();

            app('App\Http\Controllers\GlobalController')->logout();

            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    private function storeEditRules()
    {
        $store = Auth::guard('store')->user()->store ? Auth::guard('store')->user()->store->id : '';

        return [
            'slug' => 'required|max:200|unique:stores,slug,' . $store,
            'email' => 'required|max:100|unique:users,email,' . Auth::guard('store')->user()->id,
            'name' => 'required|max:200',
            'password' => 'confirmed',
            'cep' => 'required|max:10',
            'street' => 'required|max:200',
            'district' => 'required|max:100',
            'number' => 'required|max:15',
            'city' => 'required',
            'state' => 'required'
        ];
    }
}
