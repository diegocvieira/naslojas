<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\PasswordReset;
use Mail;
use Agent;
use Validator;

class PasswordResetController extends Controller
{
    public function request(Request $request)
    {
        $email = $request->email;
        $type = $request->type;

        $count = $type == 1 ? User::where('email', $email)->count() : Client::where('email', $email)->count();

        if ($count) {
            $return['status'] = true;

            // Gera o token
            $token = hash('sha256', random_bytes(32));

            // Remove tokens anteriores
            PasswordReset::where('email', $email)->where('type', $type)->delete();

            // Cria o novo token
            $pr = new PasswordReset;
            $pr->email = $email;
            $pr->token = $token;
            $pr->type = $type;
            $pr->created_at = date('Y-m-d H:i:s');
            $pr->save();

            $url = route('password-check', $token);

            Mail::send('emails.password-recover', ['url' => $url], function($q) use($email) {
                $q->from('no-reply@naslojas.com', 'naslojas');
                $q->to($email)->subject('Recuperar senha');
            });
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function check($token)
    {
        $password_recover = PasswordReset::where('token', $token)->first();

        if($password_recover) {
            $header_title = 'Recuperar senha | naslojas.com';

            if (Agent::isDesktop()) {
                return view('password-recover', compact('password_recover', 'header_title'));
            } else {
                return view('mobile.password-recover', compact('password_recover', 'header_title'));
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function change(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'confirmed|min:8'
        ], [
            'password.confirmed' => 'As senhas não conferem.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.'
        ]);

        if ($validator->fails()) {
            session()->flash('session_flash_alert', $validator->errors()->first());

            return redirect()->back();
        } else {
            $type = $request->type;
            $email = $request->email;

            // Remove o token
            PasswordReset::where('email', $email)->where('type', $type)->delete();

            // Save the new password
            $user = $type == 1 ? User::where('email', $email)->firstOrFail() : Client::where('email', $email)->firstOrFail();
            $user->password = bcrypt($request->password);
            $user->save();

            // Faz login
            $type == 1 ? app('App\Http\Controllers\UserController')->login($request) : app('App\Http\Controllers\ClientController')->login($request);

            return redirect()->route('home');
        }
    }
}
