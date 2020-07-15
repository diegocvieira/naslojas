<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\User;
use Validator;
use DB;
use Mail;
use App\Mail\StoreAccess;

class SindilojasController extends Controller
{
    public function loginIndex()
    {
        if (session('sindilojas_logged')) {
            return redirect()->route('admin.sindilojas.store.register');
        } else {
            return view('admin.sindilojas.login');
        }
    }

    public function login(Request $request)
    {
        if ($request->email === 'admin@sindilojas.com' && $request->password === 'xUc692Kb') {
            session(['sindilojas_logged' => true]);

            return redirect()->route('admin.sindilojas.store.register');
        } else {
            session()->flash('session_flash_alert', 'Não identificamos o e-mail e/ou senha que você informou.');

            return redirect()->back()->withInput($request->all());
        }
    }

    public function storeRegisterIndex()
    {
        if (!session('sindilojas_logged')) {
            return redirect()->route('admin.sindilojas.login');
        }

        return view('admin.sindilojas.store-register');
    }

    public function storeRegister(Request $request)
    {
        if (!session('sindilojas_logged')) {
            return redirect()->route('admin.sindilojas.login');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|max:100|unique:users',
                'name' => 'required|max:200'
            ],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if ($validator->fails()) {
            session()->flash('session_flash_alert', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            DB::beginTransaction();

            $store = new Store;
            $store->name = $request->name;
            $store->slug = str_slug($store->name, '-');

            // check if slug already exists and add dash in the end
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
            } while ($attempts < 10);

            $password = _generatePassword(false);

            $user = new User;
            $user->password = bcrypt($password);
            $user->email = $request->email;
            $user->store_id = $store->id;
            $user->save();

            DB::commit();

            $request->merge(['password' => $password]);

            Mail::send(new StoreAccess($request));

            session()->flash('session_flash_alert', 'Loja cadastrada com sucesso!');
        } catch (\Throwable $th) {
            DB::rollBack();

            session()->flash('session_flash_alert', 'Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.');
        }

        // Create the folder if not exists (necessary to upload images)
        // $path = public_path('uploads/' . $store->id . '/products');
        // if (!file_exists($path)) {
        //     mkdir($path, 0777, true);
        // }

        return redirect()->back();
    }
}
