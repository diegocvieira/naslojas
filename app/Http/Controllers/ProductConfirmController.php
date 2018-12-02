<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductConfirm;
use Auth;

class ProductConfirmController extends Controller
{
    public function create(Request $request)
    {
        if (isset($request->sizes)) {
            foreach ($request->sizes as $size) {
                $confirm = ProductConfirm::create([
                    'product_id' => $request->product_id,
                    'client_id' => Auth::guard('client')->user()->id,
                    'size' => $size
                ]);
            }
        } else {
            $confirm = ProductConfirm::create([
                'product_id' => $request->product_id,
                'client_id' => Auth::guard('client')->user()->id
            ]);
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
        ->orderBy('id', 'DESC')
        ->paginate(20);

        return view('client.product-confirms', compact('header_title', 'confirms'));
    }

    public function listStoreConfirms()
    {
        $header_title = 'Confirmações - naslojas.com';
        $section = 'confirm';

        $confirms = ProductConfirm::whereHas('product', function ($query) {
            $query->where('store_id', Auth::guard('store')->user()->store_id);
        })
        ->orderBy('id', 'DESC')
        ->paginate(20);

        return view('store.product-confirms', compact('header_title', 'confirms', 'section'));
    }

    public function confirm($id)
    {
        $confirm = ProductConfirm::whereHas('product', function ($query) {
            $query->where('store_id', Auth::guard('store')->user()->store_id);
        })
        ->where('id', $id)
        ->first();

        $date = date('Y-m-d H:i:s');

        $confirm->status = 1;
        $confirm->confirmed_at = $date;

        if ($confirm->save()) {
            $return['status'] = true;
            $return['date'] = date('d/m/y - H:i', strtotime($date));
            $return['type'] = 1;
            $return['msg'] = 'Confirmação realizada com sucesso! <br> O cliente já foi notificado de que o produto que ele deseja ainda encontra-se disponível.';
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function refuse($id)
    {
        $confirm = ProductConfirm::whereHas('product', function ($query) {
            $query->where('store_id', Auth::guard('store')->user()->store_id);
        })
        ->where('id', $id)
        ->first();

        $date = date('Y-m-d H:i:s');

        $confirm->status = 0;
        $confirm->confirmed_at = $date;

        if ($confirm->save()) {
            $return['status'] = true;
            $return['date'] = date('d/m/y - H:i', strtotime($date));
            $return['type'] = 0;
            $return['msg'] = 'Mantenha seus produtos atualizados. <br> Isso evita que sua loja perca pontos de relevância e seus produtos caiam de posição nas buscas.';
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }
}
