<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Client;
use Auth;
use Hash;
use Session;
use Agent;
use App\City;
use App\District;

class ClientController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                'email' => 'required|email|max:100|unique:clients',
                'password' => 'confirmed|min:8',
                'name' => 'required|max:200'
            ],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if($validator->fails()) {
            $return['msg'] = $validator->errors()->first();
            $return['status'] = false;
        } else {
            $client = new Client;
            $client->name = $request->name;
            $client->password = bcrypt($request->password);
            $client->email = $request->email;

            if($client->save()) {
                session()->flash('session_flash_alert', 'Cadastro realizado com sucesso!');

                return $this->login($request);
            } else {
                $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
                $return['status'] = false;
            }
        }

        return json_encode($return);
    }

    public function login(Request $request)
    {
        if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], true)) {
            $return['status'] = true;
            $districtId = Auth::guard('client')->user()->district_id;

            if ($districtId) {
                $this->districtSet($districtId);
            }
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não identificamos o e-mail e/ou a senha que você informou.';
        }

        return json_encode($return);
    }

    public function districtSet($districtId)
    {
        $district = District::findOrFail($districtId);

        session(['client_district_id' => $district->id]);
        session(['client_district_name' => $district->name]);

        return redirect()->back();
    }
}
