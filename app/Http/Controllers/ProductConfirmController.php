<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductConfirm;
use Auth;
use Mail;
use Agent;
use App\Product;
use App\ProductSize;

class ProductConfirmController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('store')->check()) {
                $this->store_id = Auth::guard('store')->user()->store_id;
            } else if (Auth::guard('superadmin')->check()) {
                $this->store_id = session('superadmin_store_id');
            }

            return $next($request);
        });
    }

    public function create(Request $request)
    {
        if (isset($request->sizes)) {
            foreach ($request->sizes as $size) {
                $confirm = ProductConfirm::create([
                    'product_id' => $request->product_id,
                    'client_id' => Auth::guard('client')->user()->id,
                    'size' => $size,
                    'token' => md5(uniqid(rand(), true))
                ]);

                $this->emailCreate($confirm);
            }
        } else {
            $confirm = ProductConfirm::create([
                'product_id' => $request->product_id,
                'client_id' => Auth::guard('client')->user()->id,
                'token' => md5(uniqid(rand(), true))
            ]);

            $this->emailCreate($confirm);
        }

        if($confirm) {
            $return['msg'] = 'Pedido de confirmação enviado!<br>Em breve você receberá a confirmação da loja por e-mail.';
        } else {
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function listClientConfirms()
    {
        $header_title = 'Confirmações - naslojas.com';

        $confirms = ProductConfirm::where('client_id', Auth::guard('client')->user()->id)
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
            return view('client.product-confirms', compact('header_title', 'confirms'));
        } else {
            $section = 'confirm';

            return view('mobile.client.product-confirms', compact('header_title', 'confirms', 'section'));
        }
    }

    public function listStoreConfirms()
    {
        $header_title = 'Confirmações - naslojas.com';
        $section = 'confirm';

        $confirms = ProductConfirm::whereHas('product', function ($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $this->store_id);
            })
            ->with(['product' => function($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            }])
            ->orderBy('id', 'DESC')
            ->paginate(20);

        if (Agent::isDesktop()) {
            return view('store.product-confirms', compact('header_title', 'confirms', 'section'));
        } else {
            return view('mobile.store.product-confirms', compact('header_title', 'confirms', 'section'));
        }
    }

    public function emailUrl($type, $token)
    {
        $confirm = ProductConfirm::whereHas('product', function ($query) {
                $query->withTrashed()
                    ->withoutGlobalScopes(['active', 'active-store']);
            })
            ->where('token', $token)->first();

        if ($confirm) {
            $confirm->status = $type;
            $confirm->confirmed_at = date('Y-m-d H:i:s');
            $confirm->token = null;

            if ($confirm->save()) {
                if ($type == '1') {
                    $message = 'O cliente foi notificado que o produto ainda está disponível na loja.';

                    $this->emailResponse($confirm, 1);
                } else {
                    // Desactive product or size
                    if ($confirm->size) {
                        $size = ProductSize::whereHas('product', function ($query) {
                                $query->withTrashed()
                                    ->withoutGlobalScopes(['active', 'active-store']);
                            })
                            ->where('product_id', $confirm->product_id)
                            ->where('size', $confirm->size)
                            ->first()
                            ->delete();

                        $sizes = ProductSize::whereHas('product', function ($query) {
                                $query->withTrashed()
                                    ->withoutGlobalScopes(['active', 'active-store']);
                            })
                            ->where('product_id', $confirm->product_id)
                            ->get();

                        if ($sizes->count() == 0) {
                            $p = Product::withoutGlobalScopes(['active', 'active-store'])
                                ->withTrashed()
                                ->where('id', $confirm->product_id)
                                ->first();

                            $p->status = 0;
                            $p->save();
                        }
                    } else {
                        $p = Product::withoutGlobalScopes(['active', 'active-store'])
                            ->withTrashed()
                            ->where('id', $confirm->product_id)
                            ->first();

                        $p->status = 0;
                        $p->save();
                    }

                    $message = 'O produto foi removido do site e o cliente foi notificado que o produto não está mais disponível na loja.';

                    $this->emailResponse($confirm, 0);
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

    public function confirm($id)
    {
        $confirm = ProductConfirm::whereHas('product', function ($query) {
            $query->withoutGlobalScopes(['active', 'active-store'])
                ->withTrashed()
                ->where('store_id', $this->store_id);
        })
        ->where('id', $id)
        ->first();

        $date = date('Y-m-d H:i:s');

        $confirm->status = 1;
        $confirm->confirmed_at = $date;
        $confirm->token = null;

        if ($confirm->save()) {
            $return['status'] = true;
            $return['date'] = date('d/m/y - H:i', strtotime($date));
            $return['type'] = 1;
            $return['msg'] = 'Confirmação realizada com sucesso! <br> O cliente já foi notificado de que o produto que ele deseja ainda encontra-se disponível.';

            $this->emailResponse($confirm, 1);
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function refuse($id)
    {
        $confirm = ProductConfirm::whereHas('product', function ($query) {
            $query->withoutGlobalScopes(['active', 'active-store'])
                ->withTrashed()
                ->where('store_id', $this->store_id);
        })
        ->where('id', $id)
        ->first();

        $date = date('Y-m-d H:i:s');

        $confirm->status = 0;
        $confirm->confirmed_at = $date;
        $confirm->token = null;

        if ($confirm->save()) {
            $return['status'] = true;
            $return['date'] = date('d/m/y - H:i', strtotime($date));
            $return['type'] = 0;
            $return['msg'] = 'Mantenha seus produtos atualizados. <br> Isso evita que sua loja perca pontos de relevância e seus produtos caiam de posição nas buscas.';

            // Desactive product or size
            if ($confirm->size) {
                $size = ProductSize::whereHas('product', function ($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                })
                ->where('product_id', $confirm->product_id)
                ->where('size', $confirm->size)
                ->first()
                ->delete();

                $sizes = ProductSize::whereHas('product', function ($query) {
                    $query->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store']);
                })
                ->where('product_id', $confirm->product_id)->get();

                if ($sizes->count() == 0) {
                    $p = Product::withoutGlobalScopes(['active', 'active-store'])
                        ->withTrashed()
                        ->where('id', $confirm->product_id)
                        ->first();

                    $p->status = 0;
                    $p->save();
                }
            } else {
                $p = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->withTrashed()
                    ->where('id', $confirm->product_id)
                    ->first();

                $p->status = 0;
                $p->save();
            }

            $this->emailResponse($confirm, 0);
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function emailCreate($confirm)
    {
        Mail::send('emails.store-product-confirm', ['confirm' => $confirm], function($q) use($confirm) {
            $q->from('no-reply@naslojas.com', 'naslojas');
            $q->to($confirm->product->store->user->first()->email);
            $q->subject('Nova confirmação de produto');
        });
    }

    public function emailResponse($confirm, $type)
    {
        Mail::send('emails.client-product-confirm', ['confirm' => $confirm, 'type' => $type], function($q) use($confirm) {
            $q->from('no-reply@naslojas.com', 'naslojas');
            $q->to($confirm->client->email);
            $q->subject('Confirmação de produto');
        });
    }
}
