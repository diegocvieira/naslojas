<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductReserve;
use Auth;
use Mail;
use Agent;
use App\Product;
use App\ProductSize;

class ProductReserveController extends Controller
{
    public function create(Request $request)
    {
        if (isset($request->sizes)) {
            foreach ($request->sizes as $size) {
                $reserve = ProductReserve::create([
                    'product_id' => $request->product_id,
                    'client_id' => Auth::guard('client')->user()->id,
                    'size' => $size,
                    'token' => md5(uniqid(rand(), true))
                ]);

                $this->emailCreate($reserve);
            }
        } else {
            $reserve = ProductReserve::create([
                'product_id' => $request->product_id,
                'client_id' => Auth::guard('client')->user()->id,
                'token' => md5(uniqid(rand(), true))
            ]);

            $this->emailCreate($reserve);
        }

        if($reserve) {
            $return['msg'] = 'Pedido de reserva enviado!<br>Em breve você receberá a confirmação da loja por e-mail.';
        } else {
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function emailUrl($type, $token)
    {
        $reserve = ProductReserve::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store']);
            })
            ->where('token', $token)
            ->first();

        if($reserve) {
            $reserve->status = $type;
            $reserve->confirmed_at = date('Y-m-d H:i:s');
            $reserve->token = null;

            if($reserve->save()) {
                if ($type == '1') {
                    $message = 'O cliente foi notificado que o produto está reservado para ele na loja por 24hs.';
                    $this->emailResponse($reserve, 1);
                } else {
                    // Desactive product or size
                    if ($reserve->size) {
                        $size = ProductSize::whereHas('product', function ($query) {
                            $query->withoutGlobalScopes(['active', 'active-store']);
                        })
                        ->where('product_id', $reserve->product_id)
                        ->where('size', $reserve->size)
                        ->first()
                        ->delete();

                        $sizes = ProductSize::whereHas('product', function ($query) {
                            $query->withoutGlobalScopes(['active', 'active-store']);
                        })
                        ->where('product_id', $reserve->product_id)->get();

                        if ($sizes->count() == 0) {
                            $p = Product::withoutGlobalScopes(['active', 'active-store'])
                                ->where('id', $reserve->product_id)
                                ->first();

                            $p->status = 0;
                            $p->save();
                        }
                    } else {
                        $p = Product::withoutGlobalScopes(['active', 'active-store'])
                            ->where('id', $reserve->product_id)
                            ->first();

                        $p->status = 0;
                        $p->save();
                    }

                    $message = 'O produto foi removido do site e o cliente foi notificado que o produto não está mais disponível na loja.';
                    $this->emailResponse($reserve, 0);
                }
            } else {
                $message = 'Ocorreu um erro inesperado. Acesse sua conta e realize a confirmação pelo painel de confirmações';
            }
        } else {
            $message = 'Você já respondeu a solicitação deste cliente.';
        }

        session()->flash('session_flash_alert', $message);

        return redirect()->route('home');
    }

    public function listClientReserves()
    {
        $header_title = 'Reservas - naslojas.com';

        $reserves = ProductReserve::where('client_id', Auth::guard('client')->user()->id)
            ->whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store']);
            })
            ->with(['product' => function($query) {
                $query->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderBy('id', 'DESC')
            ->paginate(20);

        if (Agent::isDesktop()) {
            return view('client.product-reserves', compact('header_title', 'reserves'));
        } else {
            return view('mobile.client.product-reserves', compact('header_title', 'reserves'));
        }
    }

    public function listStoreReserves()
    {
        $header_title = 'Reservas - naslojas.com';
        $section = 'reserve';

        $reserves = ProductReserve::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', Auth::guard('store')->user()->store_id);
            })
            ->with(['product' => function($query) {
                $query->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderBy('id', 'DESC')
            ->paginate(20);

        if (Agent::isDesktop()) {
            return view('store.product-reserves', compact('header_title', 'reserves', 'section'));
        } else {
            return view('mobile.store.product-reserves', compact('header_title', 'reserves'));
        }
    }

    public function confirm($id)
    {
        $reserve = ProductReserve::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', Auth::guard('store')->user()->store_id);
            })
            ->where('id', $id)
            ->first();

        $date = date('Y-m-d H:i:s');

        $reserve->status = 1;
        $reserve->confirmed_at = $date;
        $reserve->reserved_until = date('Y-m-d H:i:s', strtotime($date . '+1 day'));
        $reserve->token = null;

        if ($reserve->save()) {
            $return['status'] = true;
            $return['date_confirmed'] = date('d/m/y - H:i', strtotime($date));
            $return['date_reserved'] = date('d/m/y - H:i', strtotime($date . '+1 day'));
            $return['type'] = 1;
            $return['msg'] = 'Reserva realizada com sucesso! <br> O cliente já foi notificado de que o produto que ele deseja estará aguardando por ele na loja por 24hs.';

            $this->emailResponse($reserve, 1);
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function refuse($id)
    {
        $reserve = ProductReserve::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', Auth::guard('store')->user()->store_id);
            })
            ->where('id', $id)
            ->first();

        $date = date('Y-m-d H:i:s');

        $reserve->status = 0;
        $reserve->confirmed_at = $date;
        $reserve->token = null;

        if ($reserve->save()) {
            $return['status'] = true;
            $return['date_confirmed'] = date('d/m/y - H:i', strtotime($date));
            $return['type'] = 0;
            $return['msg'] = 'Mantenha seus produtos atualizados. <br> Isso evita que sua loja perca pontos de relevância e seus produtos caiam de posição nas buscas.';

            $this->emailResponse($reserve, 0);
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function emailCreate($reserve)
    {
        Mail::send('emails.store-product-reserve', ['reserve' => $reserve], function($q) use($reserve) {
            $q->from('no-reply@naslojas.com', 'naslojas');
            $q->to($reserve->product->store->user->first()->email);
            $q->subject('Nova reserva de produto');
        });
    }

    public function emailResponse($reserve, $type)
    {
        Mail::send('emails.client-product-reserve', ['reserve' => $reserve, 'type' => $type], function($q) use($reserve) {
            $q->from('no-reply@naslojas.com', 'naslojas');
            $q->to($reserve->client->email);
            $q->subject('Reserva de produto');
        });
    }
}
