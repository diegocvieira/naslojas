<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Store;
use App\User;
use App\SuperAdmin;
use App\SuperAdminStore;
use Auth;
use Validator;

class SuperadminController extends Controller
{
    public function storeRegister(Request $request)
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
            session()->flash('session_flash_alert', $validator->errors()->first());

            return redirect()->route('superadmin-store-register')->withInput();
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

            if (Auth::guard('superadmin')->user()->type == 2) {
                $store->superadmin()->create([
                    'superadmin_id' => Auth::guard('superadmin')->user()->id
                ]);
            }

            $user = new User;
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->store_id = $store->id;
            $user->save();

            // Create the folder if not exists (necessary to uploads images)
            $path = public_path('uploads/' . $store->id . '/products');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $this->setStore($store->id);

            session()->flash('session_flash_alert', 'Cadastro realizado com sucesso!');

            return redirect()->route('product-images');
        }
    }

    public function login(Request $request)
    {
        if (Auth::guard('superadmin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('superadmin-index');
        } else {
            session()->flash('session_flash_alert', 'Não identificamos o e-mail e/ou a senha que você informou.');

            return redirect()->route('superadmin-login');
        }
    }

    public function setStore($id)
    {
        $store = Store::select('id', 'slug', 'status')->find($id);

        session(['superadmin_store_id' => $store->id]);
        session(['superadmin_store_slug' => $store->slug]);
        session(['superadmin_store_status' => $store->status]);
        session(['superadmin_user_id' => $store->user->first()->id]);

        return redirect()->route('edit-products');
    }
}
