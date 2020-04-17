<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use Cookie;
use Auth;
use Session;
use App\Product;
use Agent;
use App\Store;
use Illuminate\Support\Facades\Cache;

class GlobalController extends Controller
{
    public function home()
    {
        if (!Cookie::get('city_slug') || Cookie::get('city_slug') != 'sao-paulo') {
            $city = City::whereHas('state', function ($query) {
                    $query->where('letter', 'sp');
                })
                ->where('slug', 'sao-paulo')
                ->select('id')
                ->first();

            $this->setCity($city->id);
        }

        // session(['session_modal_home' => (session('session_modal_home') ? 'false' : 'true')]);

        $stores = Cache::remember('stores' . session('client_district_id'), 24*60*60, function () {
            return Store::isActive()
                ->clientDistrict()
                ->inRandomOrder()
                ->get();
        });

        // $featured_products = Product::has('images')
        //     ->where(function($q) {
        //         $q->where('identifier', '1369251634')
        //             ->orWhere('identifier', '7682726781')
        //             ->orWhere('identifier', '6284040866')
        //             ->orWhere('identifier', '1240659416')
        //             ->orWhere('identifier', '6813927890')
        //             ->orWhere('identifier', '2824134342')
        //             ->orWhere('identifier', '4360979453')
        //             ->orWhere('identifier', '8735695318')
        //             ->orWhere('identifier', '7398215376')
        //             ->orWhere('identifier', '3118471542')
        //             ->orWhere('identifier', '3428785629')
        //             ->orWhere('identifier', '9462447778')
        //             ->orWhere('identifier', '6258734190')
        //             ->orWhere('identifier', '8431415355')
        //             ->orWhere('identifier', '6570416578')
        //             ->orWhere('identifier', '8563896576')
        //             ->orWhere('identifier', '4998815173')
        //             ->orWhere('identifier', '1797473126')
        //             ->orWhere('identifier', '7702900754')
        //             ->orWhere('identifier', '4816368777')
        //             ->orWhere('identifier', '2908097247')
        //             ->orWhere('identifier', '7374498917')
        //             ->orWhere('identifier', '2602996432')
        //             ->orWhere('identifier', '3274134989')
        //             ->orWhere('identifier', '9500944073');
        //     })
        //     ->inRandomOrder()
        //     ->get();

        // $offers = Product::has('images')
        //     ->where(function($q) {
        //         $q->where('identifier', '1545220228')
        //             ->orWhere('identifier', '1522012655')
        //             ->orWhere('identifier', '9527520242')
        //             ->orWhere('identifier', '3144605048')
        //             ->orWhere('identifier', '5598752579')
        //             ->orWhere('identifier', '9271930957')
        //             ->orWhere('identifier', '5183128253')
        //             ->orWhere('identifier', '8912953312')
        //             ->orWhere('identifier', '5569228901')
        //             ->orWhere('identifier', '7540756864')
        //             ->orWhere('identifier', '5550121493')
        //             ->orWhere('identifier', '7631231647')
        //             ->orWhere('identifier', '5465437311')
        //             ->orWhere('identifier', '3540796156')
        //             ->orWhere('identifier', '7283592430')
        //             ->orWhere('identifier', '8379221556')
        //             ->orWhere('identifier', '6482179871')
        //             ->orWhere('identifier', '7292324672')
        //             ->orWhere('identifier', '1688774253')
        //             ->orWhere('identifier', '5673353306')
        //             ->orWhere('identifier', '8402541905')
        //             ->orWhere('identifier', '6032595789')
        //             ->orWhere('identifier', '2998447243')
        //             ->orWhere('identifier', '8115705904')
        //             ->orWhere('identifier', '6388814493');
        //     })
        //     ->inRandomOrder()
        //     ->get();

        // $trending_words = ['melissa', 'krause', 'hercílio', 'emilice', 'sapato', 'tênis', 'sandália', 'bota', 'salto alto', 'scarpin', 'camisa', 'camiseta', 'vestido', 'saia', 'short', 'jeans', 'casaco', 'jaqueta', 'masculino', 'feminino', 'nike', 'adidas', 'schutz', 'colcci', 'livro', 'óculos de sol', 'maquiagem'];
        // shuffle($trending_words);

        // $brands = ['adidas', 'asics', 'bebece', 'bull terrier', 'colcci', 'grendene', 'melissa', 'nike', 'olympikus', 'ramarim', 'schutz', 'via marte'];
        // shuffle($brands);



        if (Agent::isDesktop()) {
            return view('home', compact('stores'));
        } else {
            return view('mobile.home', compact('stores'));
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
        // Session::flush();

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
