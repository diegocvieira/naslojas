<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use Hash;
use App\District;
use Auth;
use Validator;
use App\City;
use Agent;

class ClientController extends Controller
{
    public function getConfig($navigation = null)
    {
        $header_title = 'Configurações | naslojas.com';

        $client = Client::find(Auth::guard('client')->user()->id);

        $districts = District::orderBy('name', 'ASC')->pluck('name', 'id');

        if (Agent::isDesktop()) {
            return view('client.config', compact('client', 'header_title', 'navigation', 'districts'));
        } else {
            $section = 'config';

            return view('mobile.client.config', compact('client', 'section', 'districts', 'navigation'));
        }
    }

    public function setConfig(Request $request)
    {
        $section = $request->section;
        $user_id = Auth::guard('client')->user()->id;

        if ($section == 'profile') {
            $rules = [
                'name' => 'required|max:200',
                'phone' => 'required|max:15',
                'cpf' => 'required|max:15'
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
                'email' => 'required|email|max:65|unique:clients,email,' . $user_id,
                'password' => 'confirmed'
            ];
        } else {
            $rules = [];
        }

        $validate = Validator::make(
            $request->all(),
            $rules,
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

         if ($validate->fails()) {
             $data['message'] = $validate->errors()->first();
             return response()->json($data);
         }

        $client = Client::find($user_id);

        if ($section == 'address') {
            $city = City::whereHas('state', function ($query) use ($request) {
                    $query->where('letter', $request->state);
                })
                ->where('title', 'LIKE', '%' . $request->city . '%')
                ->select('id', 'slug')
                ->first();

            if (!$city || !$city->isAvailable()) {
                $data['message'] = 'Nossa entrega ainda não está disponível na sua região.';
                return response()->json($data);
            }

            $client->city_id = $city->id;
        }

        $client->name = $request->name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->birthdate = $request->birthdate ? date('Y-m-d', strtotime(str_replace('/', '-', $request->birthdate))) : null;
        $client->cpf = $request->cpf;
        $client->cep = $request->cep;
        $client->street = $request->street;
        $client->district_id = $request->district;
        $client->number = $request->number;
        $client->complement = $request->complement;

        if ($request->password) {
            $client->password = bcrypt($request->password);
        }

        try {
            $client->save();

            $data['message'] = 'Informações atualizadas.';
        } catch (\Throwable $th) {
            $data['message'] = 'Ocorreu um erro inesperado. Tente novamente.';
        }

        return response()->json($data);
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
}
