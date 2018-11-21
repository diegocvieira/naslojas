<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use App\Product;
use Auth;

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

    public function register()
    {
        $products = Product::where('status', 2)->get();

        $section = 'add';

        if (count($products) > 0) {

        } else {
            return view('store.product-images', compact('section'));
        }
    }

    public function uploadImages(Request $request)
    {
        $rules = ['file' => 'image|max:3000'];

        $validation = \Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return \Response::make($validation->errors->first(), 400);
        }

        $destinationPath = 'uploads/' . Auth::guard('store')->user()->store_id . '/produtos'; // upload path
        $extension = $request->file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
        $upload_success = $request->file('file')->move($destinationPath, $fileName); // uploading file to given path

        if ($upload_success) {
            return \Response::json($fileName, 200);
        } else {
            return \Response::json('error', 400);
        }
    }

    public function storeImages()
    {

    }
}
