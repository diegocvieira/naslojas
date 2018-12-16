<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Store;
use App\User;

class SuperadminController extends Controller
{
    public function getStoreRegister()
    {
        if (Session::has('superadmin_logged')) {
            return view('superadmin.store-register');
        } else {
            return redirect()->route('superadmin-login');
        }
    }

    public function postStoreRegister(Request $request)
    {
        if (Session::has('superadmin_logged')) {
            $store = new Store;
            $store->save();

            $user = new User;
            $user->password = bcrypt($request->password);
            $user->email = $request->email;
            $user->store_id = $store->id;

            if ($user->save()) {
                // Create the folder if not exists (necessary to uploads images)
                $path = public_path('uploads/' . $store->id . '/products');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $alert = 'Cadastro realizado com sucesso!';
            } else {
                $alert = 'Ocorreu um erro inesperado. Tente novamente.';
            }

            session()->flash('session_flash_alert', $alert);

            return redirect()->route('superadmin-store-register');
        } else {
            return redirect()->route('superadmin-login');
        }
    }

    public function login(Request $request)
    {
        if ($request->email == 'contato@naslojas.com' && $request->password == 'Om35i7D9Dmsy') {
            session(['superadmin_logged' => 'true']);

            return redirect()->route('superadmin-store-register');
        } else {
            session()->flash('session_flash_alert', 'Não identificamos o e-mail e/ou a senha que você informou.');

            return redirect()->route('superadmin-login');
        }
    }
}
