<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use Cookie;
use Auth;
use Session;
use App\Product;
use Agent;

class GlobalController extends Controller
{
    public function pdf(Request $request)
    {
        $product = Product::has('images')->where('id', $request->product_id)->first();

        $image = new \Imagick(public_path('images/post/template.png'));
        $draw = new \ImagickDraw();

        $draw->setStrokeWidth(3);
        $draw->setFont(resource_path('fonts/OpenSans-Regular.ttf'));

        // DESLIZAR OU SLUG DA LOJA
        if ($request->option == 1) {
            $image_slide = new \Imagick(public_path('images/post/store-slug.png'));
            $image->compositeImage($image_slide, \Imagick::COMPOSITE_OVER, 75, 1600);

            $draw->setFillColor('#000');
            $draw->setStrokeColor('#000');
            $draw->setFontSize(50);
            $draw->annotation(400, 1690, $product->store->slug);
        } else {
            $image_slide = new \Imagick(public_path('images/post/slide.png'));
            $image->compositeImage($image_slide, \Imagick::COMPOSITE_OVER, 300, 1600);
        }

        // TEMPO DE DESCONTO
        if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time)) {
            $draw->setFillColor('#000');
            $draw->setStrokeColor('#000');
            $draw->setFontSize(62);
            $draw->annotation(685, 1178, $product->offtime->time . 'HS');

            $image_offtime = new \Imagick(public_path('images/post/offtime.png'));
            $image->compositeImage($image_offtime, \Imagick::COMPOSITE_OVER, 175, 1120);

            $percetage = $product->offtime->off;
        } else {
            $image_offtime = new \Imagick(public_path('images/post/special-off.png'));
            $image->compositeImage($image_offtime, \Imagick::COMPOSITE_OVER, 195, 1120);
        }

        // IMAGEM DO PRODUTO
        $image_product = new \Imagick(public_path('uploads/' . $product->store_id . '/products/' . _originalImage($product->images->first()->image)));
        $image_product->resizeImage(830, 830, \imagick::FILTER_LANCZOS, 1, TRUE);
        $image_product_width = ((830 - $image_product->getImageWidth()) / 2) + 125;
        $image_product_height = ((830 - $image_product->getImageHeight()) / 2) + 235;
        $image->compositeImage($image_product, \Imagick::COMPOSITE_OVER, $image_product_width, $image_product_height);

        // PORCENTAGEM DE DESCONTO
        if (!isset($percetage) && $product->off) {
            $percetage = $product->off;
        }

        if (isset($percetage)) {
            $draw->setFillColor('#fff');
            $draw->setStrokeColor('#fff');
            $draw->setFontSize(50);
            $draw->rotate(15);
            $draw->annotation(910, -5, $percetage . '%');

            $image_off = new \Imagick(public_path('images/post/off.png'));
            $image->compositeImage($image_off, \Imagick::COMPOSITE_OVER, 810, 140);
        }

        // FRETE GRATIS
        if ($product->free_freight) {
            $image_freefreight = new \Imagick(public_path('images/post/free-freight.png'));
            $image->compositeImage($image_freefreight, \Imagick::COMPOSITE_OVER, 440, 1415);
        } else {
            $image_freefreight = new \Imagick(public_path('images/post/freight.png'));
            $image->compositeImage($image_freefreight, \Imagick::COMPOSITE_OVER, 420, 1415);
        }

        $image_name = microtime(true) . '.png';

        $image->drawImage($draw);
        $image->writeImage(public_path('uploads/' . $product->store_id . '/' . $image_name));

        $return['status'] = true;
        $return['url'] = asset('uploads/' . $product->store_id . '/' . $image_name);

        return json_encode($return);
    }

    public function home()
    {
        if (!Cookie::get('city_slug') || Cookie::get('city_slug') != 'pelotas') {
            $this->setCity(4913);
        }

        session(['session_modal_home' => (session('session_modal_home') ? 'false' : 'true')]);

        $featured_products = Product::has('images')
            ->where(function($q) {
                $q->where('identifier', '1369251634')
                    ->orWhere('identifier', '7682726781')
                    ->orWhere('identifier', '6284040866')
                    ->orWhere('identifier', '1240659416')
                    ->orWhere('identifier', '6813927890')
                    ->orWhere('identifier', '2824134342')
                    ->orWhere('identifier', '4360979453')
                    ->orWhere('identifier', '8735695318')
                    ->orWhere('identifier', '7398215376')
                    ->orWhere('identifier', '3118471542')
                    ->orWhere('identifier', '3428785629')
                    ->orWhere('identifier', '9462447778')
                    ->orWhere('identifier', '6258734190')
                    ->orWhere('identifier', '8431415355')
                    ->orWhere('identifier', '6570416578')
                    ->orWhere('identifier', '8563896576')
                    ->orWhere('identifier', '4998815173')
                    ->orWhere('identifier', '1797473126')
                    ->orWhere('identifier', '7702900754')
                    ->orWhere('identifier', '4816368777')
                    ->orWhere('identifier', '2908097247')
                    ->orWhere('identifier', '7374498917')
                    ->orWhere('identifier', '2602996432')
                    ->orWhere('identifier', '3274134989')
                    ->orWhere('identifier', '9500944073');
            })
            ->inRandomOrder()
            ->get();

        $offers = Product::has('images')
            ->where(function($q) {
                $q->where('identifier', '1545220228')
                    ->orWhere('identifier', '1522012655')
                    ->orWhere('identifier', '9527520242')
                    ->orWhere('identifier', '3144605048')
                    ->orWhere('identifier', '5598752579')
                    ->orWhere('identifier', '9271930957')
                    ->orWhere('identifier', '5183128253')
                    ->orWhere('identifier', '8912953312')
                    ->orWhere('identifier', '5569228901')
                    ->orWhere('identifier', '7540756864')
                    ->orWhere('identifier', '5550121493')
                    ->orWhere('identifier', '7631231647')
                    ->orWhere('identifier', '5465437311')
                    ->orWhere('identifier', '3540796156')
                    ->orWhere('identifier', '7283592430')
                    ->orWhere('identifier', '8379221556')
                    ->orWhere('identifier', '6482179871')
                    ->orWhere('identifier', '7292324672')
                    ->orWhere('identifier', '1688774253')
                    ->orWhere('identifier', '5673353306')
                    ->orWhere('identifier', '8402541905')
                    ->orWhere('identifier', '6032595789')
                    ->orWhere('identifier', '2998447243')
                    ->orWhere('identifier', '8115705904')
                    ->orWhere('identifier', '6388814493');
            })
            ->inRandomOrder()
            ->get();

        $trending_words = ['melissa', 'krause', 'hercílio', 'emilice', 'sapato', 'tênis', 'sandália', 'bota', 'salto alto', 'scarpin', 'camisa', 'camiseta', 'vestido', 'saia', 'short', 'jeans', 'casaco', 'jaqueta', 'masculino', 'feminino', 'nike', 'adidas', 'schutz', 'colcci', 'livro', 'óculos de sol', 'maquiagem'];
        shuffle($trending_words);

        $brands = ['adidas', 'asics', 'bebece', 'bull terrier', 'colcci', 'grendene', 'melissa', 'nike', 'olympikus', 'ramarim', 'schutz', 'via marte'];
        shuffle($brands);

        if (Agent::isDesktop()) {
            return view('home', compact('featured_products', 'offers', 'trending_words', 'brands'));
        } else {
            return view('mobile.home', compact('featured_products', 'offers', 'trending_words', 'brands'));
        }
    }

    public function setCity($id)
    {
        $city = City::find($id);

        _setCity($city, true);

        return redirect()->route('home');
    }

    public function logout()
    {
        Session::flush();

        if (Auth::guard('store')->check()) {
            Auth::guard('store')->logout();
        } else {
            Auth::guard('client')->logout();
        }

        return redirect()->route('home');
    }

    public function customMessages()
    {
        return [
            'name.required' => 'Informe o nome.',
            'name.max' => 'O nome deve ter menos de 200 caracteres.',
            'slug.max' => 'A url deve ter menos de 200 caracteres.',
            'slug.required' => 'Informe uma url.',
            'slug.unique' => 'Esta url já está sendo utilizada por outro usuário.',
            'image.image' => 'Imagem inválida',
            'image.max' => 'A imagem tem que ter no máximo 5mb.',
            'cep.required' => 'Informe o CEP.',
            'cep.max' => 'O CEP deve ter menos de 10 caracteres.',
            'street.required' => 'Informe o logradouro.',
            'street.max' => 'O logradouro deve ter menos de 200 caracteres.',
            'district.required' => 'Informe o bairro.',
            'district.max' => 'O bairro deve ter menos de 100 caracteres.',
            'number.required' => 'Informe o número.',
            'number.max' => 'O número deve ter menos de 15 caracteres.',
            'city.required' => 'Informe a cidade.',
            'state.required' => 'Informe o estado.',
            'email.max' => 'Seu e-mail deve ter menos de 100 caracteres.',
            'email.required' => 'Precisamos saber o seu e-mail.',
            'email.email' => 'Seu endereço de e-mail é inválido.',
            'email.unique' => 'Este email já está sendo utilizado por outro usuário.',
            'password.confirmed' => 'As senhas não conferem.',
            'password.min' => 'Sua senha deve ter no mínimo 8 caracteres.',
            'title.required' => 'Informe o título do produto.',
            'title.max' => 'O título deve ter no máximo 255 caracteres.',
            'price.required' => 'Informe o preço do produto.',
            'gender.required' => 'Informe o gênero do produto.',
            'description.max' => 'A descrição deve ter no máximo 2000 caracteres.',
            'max_product_unit.numeric' => 'Informe apenas números inteiros.',
            'max_parcel.numeric' => 'Informe apenas números inteiros.',
            'min_parcel_price.required' => 'Informe o valor mínimo de cada parcela.',
            'phone.required' => 'Informe o número de telefone.',
            'phone.max' => 'O telefone deve ter no máximo 15 caracteres',
            'cnpj.required' => 'Informe o CNPJ.',
            'cnpj.max' => 'O CNPJ deve ter no máximo 18 caracteres.',
            'cpf.required' => 'Informe o CPF',
            'cpf.max' => 'O CPF deve ter no máximo 15 caracteres.',
            'freight.required' => 'Informe o tipo de reserva.',
            'reserve_date' => 'Informe o horário de entrega'
        ];
    }
}
