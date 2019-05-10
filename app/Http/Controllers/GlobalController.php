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
    public function home()
    {
        if (!Cookie::get('city_slug') || Cookie::get('city_slug') != 'pelotas') {
            $this->setCity(4913);
        }

        $products = Product::has('images')->inRandomOrder()->paginate(30);

        $featured_products = Product::has('images')->inRandomOrder()->limit(15)->get();

        $offers = Product::has('images')->inRandomOrder()->limit(15)->get();

        $trending_words = ['melissa', 'krause', 'hercilio', 'emilice', 'sapato', 'calcado', 'tenis', 'sandalia', 'bota', 'salto alto', 'scarpin', 'camisa', 'camiseta', 'vestido', 'saia', 'bermuda', 'short', 'jeans', 'casaco', 'jaqueta', 'masculino', 'feminino', 'nike', 'adidas', 'mizuno', 'schutz', 'colcci', 'celular', 'livro', 'oculos de sol', 'maquiagem'];
        shuffle($trending_words);

        $brands = ['adidas', 'asics', 'bebece', 'bull-terrier', 'colcci', 'grendene', 'melissa', 'nike', 'olympikus', 'ramarim', 'schutz', 'via-marte'];
        shuffle($brands);

        if (Agent::isDesktop()) {
            return view('home', compact('featured_products', 'offers', 'trending_words', 'brands'));
        } else {
            return view('mobile.home', compact('products'));
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
