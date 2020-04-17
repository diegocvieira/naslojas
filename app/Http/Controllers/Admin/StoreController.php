<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Store;
use App\City;
use App\User;
use App\Product;
use App\District;
use Hash;
use Validator;
use Agent;

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

    public function getConfig($navigation = null)
    {
        $section = 'config';
        $header_title = 'Configurações | naslojas.com';

        $user = User::find($this->user_id);
        $districts = District::orderBy('name', 'ASC')->get();

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
            $rules = [
                'slug' => 'required|max:200|unique:stores,slug,' . $this->store_id,
                'name' => 'required|max:200',
                'phone' => 'required|max:15',
                'cnpj' => 'required|max:18'
            ];
        } else if ($section == 'address') {
            $rules = [
                'cep' => 'required|max:10',
                'street' => 'required|max:200',
                'district' => 'required|max:100',
                'number' => 'required|max:15',
                'city' => 'required',
                'state' => 'required'
            ];
        } else if ($section == 'access') {
            $rules = [
                'email' => 'required|max:100|unique:users,email,' . $this->user_id,
                'password' => 'confirmed'
            ];
        } else if ($section == 'payment') {
            $rules = [
                'max_product_unit' => 'numeric',
                'max_parcel' => 'numeric',
                'min_parcel_price' => 'required'
            ];
        } else {
            $rules = [];
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

         if ($validator->fails()) {
             $data['message'] = $validator->errors()->first();
             return response()->json($data);
        }

        $user = User::find($this->user_id);
        $store = Store::find($user->store_id);

        if ($section == 'address') {
            $city = City::whereHas('state', function ($query) use ($request) {
                    $query->where('letter', $request->state);
                })
                ->where('title', 'LIKE', '%' . $request->city . '%')
                ->select('id', 'slug')->first();

            if (!$city || !$city->isAvailable()) {
                $data['message'] = 'Nossa entrega ainda não está disponível na sua região.';
                return response()->json($data);
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

        try {
            $store->save();
            $user->save();

            if (Auth::guard('superadmin')->check()) {
                app('App\Http\Controllers\SuperadminController')->setStore($store->id);
            }

            $data['message'] = 'Informações atualizadas.';
        } catch (\Throwable $th) {
            $data['message'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return response()->json($data);
    }
}
