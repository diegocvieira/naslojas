<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use App\Product;

class ProductController extends Controller
{
    public function formSearch(Request $request)
    {
        $city = Cookie::get('city_slug');
        $state = Cookie::get('state_letter_lc');
        $gender = $request->gender;
        $order = $request->order;
        $keyword = urlencode($request->keyword);

        if ($keyword && !$order) {
            $order = 'palavra-chave';
        }

        return redirect()->action('ProductController@search', [$city, $state, $gender, $order, $keyword]);
    }

    public function search($city_slug, $state_letter_lc, $search_gender, $search_order = null, $keyword = null)
    {
        $products = Product::filterGender($search_gender)->filterOrder($search_order);

        if ($keyword) {
            $keyword = urldecode($keyword);

            // SEO
            $header_title = $keyword .' em ' . Cookie::get('city_title') . ' - ' . Cookie::get('state_letter') . ' | naslojas.com';
            $header_desc = 'Clique para ver ' . $keyword . ' em ' . Cookie::get('city_title') . ' - ' . Cookie::get('state_letter');

            $keyword = str_replace('-', ' ', $keyword);

            // separa cada palavra
            $keyword_array = explode(' ', $keyword);

            // se houver mais de 2 palavras e a palavra tiver menos de 4 letras ignora na busca
            foreach ($keyword_array as $keyword_each) {
                if (count($keyword_array) > 2 && strlen($keyword) < 4) {
                    continue;
                }

                $products = $products->where(function ($query) use ($keyword_each) {
                    $query->where('titulo', 'LIKE', '%' . $keyword_each . '%')->orWhereHas('store', function ($query) use ($keyword_each) {
                        $query->where('nome', 'LIKE', '%' . $keyword_each . '%');
                    });
                });
            }
        }

        $products = $products->paginate(20);

        return view('home', compact('products', 'keyword', 'search_gender', 'search_order', 'header_title', 'header_desc'));
    }
}
