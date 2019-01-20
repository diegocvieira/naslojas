<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Auth;
use Mail;
use Agent;

class MessageController extends Controller
{
    public function createClientMessage(Request $request)
    {
        if($request->message) {
            $message = Message::create([
                'product_id' => $request->product_id,
                'client_id' => Auth::guard('client')->user()->id,
                'question' => $request->message
            ]);

            if($message) {
                $return['msg'] = 'Mensagem enviada com sucesso! <br> Aguarde a loja entrar em contato.';
                $return['status'] = true;
                $return['user_name'] = Auth::guard('client')->user()->name;

                Mail::send('emails.store-message', ['message' => $message], function($q) use($message) {
                    $q->from('no-reply@naslojas.com', 'naslojas');
                    $q->to($message->product->store->user->first()->email);
                    $q->subject('Nova mensagem');
                });
            } else {
                $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
                $return['status'] = false;
            }

            return json_encode($return);
        }
    }

    public function createStoreMessage(Request $request)
    {
        if($request->message) {
            $message = Message::find($request->id);

            $date = date('Y-m-d H:i:s');

            $message->response = $request->message;
            $message->status = 1;
            $message->answered_at = $date;

            if($message->save()) {
                $return['msg'] = 'Mensagem enviada com sucesso!';
                $return['status'] = true;
                $return['date'] = date('d/m/y - H:i', strtotime($date));

                Mail::send('emails.client-message', ['message' => $message], function($q) use($message) {
                    $q->from('no-reply@naslojas.com', 'naslojas');
                    $q->to($message->client->email);
                    $q->subject('Nova mensagem');
                });
            } else {
                $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
                $return['status'] = false;
            }

            return json_encode($return);
        }
    }

    public function listClientMessages()
    {
        $header_title = 'Mensagens - naslojas.com';

        $messages = Message::where('client_id', Auth::guard('client')->user()->id)
            ->whereHas('product', function ($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            })
            ->with(['product' => function($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderBy('id', 'DESC')
            ->paginate(20);

        if (Agent::isDesktop()) {
            return view('client.messages', compact('header_title', 'messages'));
        } else {
            $section = 'message';

            return view('mobile.client.messages', compact('header_title', 'messages', 'section'));
        }
    }

    public function listStoreMessages()
    {
        $header_title = 'Mensagens - naslojas.com';
        $section = 'message';

        $messages = Message::whereHas('product', function ($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', Auth::guard('store')->user()->store_id);
            })
            ->with(['product' => function($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderBy('id', 'DESC')
            ->paginate(20);

        if (Agent::isDesktop()) {
            return view('store.messages', compact('header_title', 'messages', 'section'));
        } else {
            return view('mobile.store.messages', compact('header_title', 'messages', 'section'));
        }
    }
}
