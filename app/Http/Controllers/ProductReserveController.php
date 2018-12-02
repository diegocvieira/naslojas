<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductReserve;
use Auth;

class ProductReserveController extends Controller
{
    public function create(Request $request)
    {
        if (isset($request->sizes)) {
            foreach ($request->sizes as $size) {
                $confirm = ProductReserve::create([
                    'product_id' => $request->product_id,
                    'client_id' => Auth::guard('client')->user()->id,
                    'size' => $size
                ]);
            }
        } else {
            $confirm = ProductReserve::create([
                'product_id' => $request->product_id,
                'client_id' => Auth::guard('client')->user()->id
            ]);
        }

        if($confirm) {
            $return['msg'] = 'Pedido de reserva enviado!<br>Em breve você receberá a confirmação da loja por e-mail.';
        } else {
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function listClientReserves()
    {
        $header_title = 'Reservas - naslojas.com';

        $reserves = ProductReserve::where('client_id', Auth::guard('client')->user()->id)
        ->orderBy('id', 'DESC')
        ->paginate(20);

        return view('client.product-reserves', compact('header_title', 'reserves'));
    }

    public function listStoreReserves()
    {
        $header_title = 'Reservas - naslojas.com';
        $section = 'reserve';

        $reserves = ProductReserve::whereHas('product', function ($query) {
            $query->where('store_id', Auth::guard('store')->user()->store_id);
        })
        ->orderBy('id', 'DESC')
        ->paginate(20);

        return view('store.product-reserves', compact('header_title', 'reserves', 'section'));
    }

    public function confirm($id)
    {
        $reserve = ProductReserve::whereHas('product', function ($query) {
            $query->where('store_id', Auth::guard('store')->user()->store_id);
        })
        ->where('id', $id)
        ->first();

        $date = date('Y-m-d H:i:s');

        $reserve->status = 1;
        $reserve->confirmed_at = $date;
        $reserve->reserved_until = date('Y-m-d H:i:s', strtotime($date . '+1 day'));

        if ($reserve->save()) {
            $return['status'] = true;
            $return['date_confirmed'] = date('d/m/y - H:i', strtotime($date));
            $return['date_reserved'] = date('d/m/y - H:i', strtotime($date . '+1 day'));
            $return['type'] = 1;
            $return['msg'] = 'Reserva realizada com sucesso! <br> O cliente já foi notificado de que o produto que ele deseja estará aguardando por ele na loja por 24hs.';
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function refuse($id)
    {
        $reserve = ProductReserve::whereHas('product', function ($query) {
            $query->where('store_id', Auth::guard('store')->user()->store_id);
        })
        ->where('id', $id)
        ->first();

        $date = date('Y-m-d H:i:s');

        $reserve->status = 0;
        $reserve->confirmed_at = $date;

        if ($reserve->save()) {
            $return['status'] = true;
            $return['date_confirmed'] = date('d/m/y - H:i', strtotime($date));
            $return['type'] = 0;
            $return['msg'] = 'Mantenha seus produtos atualizados. <br> Isso evita que sua loja perca pontos de relevância e seus produtos caiam de posição nas buscas.';
        } else {
            $return['status'] = false;
            $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
        }

        return json_encode($return);
    }
}
