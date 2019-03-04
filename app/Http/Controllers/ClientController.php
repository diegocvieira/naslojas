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
            $request->all(),
            $this->clientRegisterRules(),
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
        } else {
            $return['status'] = false;
            $return['msg'] = 'Não identificamos o e-mail e/ou a senha que você informou.';
        }

        return json_encode($return);
    }

    public function getConfig($navigation = null)
    {
        $header_title = 'Configurações | naslojas.com';

        $client = Client::find(Auth::guard('client')->user()->id);

        $districts = District::orderBy('name', 'ASC')->pluck('name', 'id');

        if (Agent::isDesktop()) {
            return view('client.config', compact('client', 'header_title', 'navigation', 'districts'));
        } else {
            $section = 'config';

            return view('mobile.client.config', compact('client', 'section', 'districts'));
        }
    }

    public function setConfig(Request $request)
    {
        $section = $request->section;

        if ($section == 'profile') {
            $rules = $this->profileRules();
        } else if ($section == 'address') {
            $rules = $this->addressRules();
        } else if ($section == 'access') {
            $rules = $this->accessRules();
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
            if (Hash::check($request->current_password, Auth::guard('client')->user()->password)) {
                $client = Client::find(Auth::guard('client')->user()->id);

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

                    $client->city_id = $city->id;
                }

                $client->name = $request->name;
                $client->email = $request->email;
                $client->phone = $request->phone;
                $client->birthdate = $request->birthdate;
                $client->cpf = $request->cpf;
                $client->cep = $request->cep;
                $client->street = $request->street;
                $client->district_id = $request->district;
                $client->number = $request->number;
                $client->complement = $request->complement;

                if ($request->password) {
                    $client->password = bcrypt($request->password);
                }

                if ($client->save()) {
                    $return['msg'] = 'Informações atualizadas.';
                    $return['status'] = 1;
                } else {
                    $return['msg'] = 'Ocorreu um erro inesperado. Tente novamente.';
                    $return['status'] = 0;
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
        if (Hash::check($request->password, Auth::guard('client')->user()->password)) {
            Client::find(Auth::guard('client')->user()->id)->delete();

            app('App\Http\Controllers\GlobalController')->logout();

            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    private function clientRegisterRules()
    {
        return [
            'email' => 'required|email|max:100|unique:clients',
            'password' => 'confirmed|min:8',
            'name' => 'required|max:200'
        ];
    }

    private function profileRules()
    {
        return [
            'name' => 'required|max:200',
            'phone' => 'required|max:15',
            'cpf' => 'required|max:15'
        ];
    }

    private function accessRules()
    {
        return [
            'email' => 'required|email|max:65|unique:clients,email,' . Auth::guard('client')->user()->id,
            'password' => 'confirmed'
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
}
