<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use App\Product;
use Auth;
use Response;
use Validator;
use App\ProductImage;
use App\ProductSize;
use App\ProductRating;
use App\Store;
use DB;
use Agent;
use Session;

class ProductController extends Controller
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

    public function relatedProducts(Product $product, $pagination = null)
    {
        $related_products = Product::where('id', '!=', $product->id)
            ->has('images')
            ->whereHas('store', function ($query) use ($product) {
                $query->where('city_id', $product->store->city->id);
            })
            ->where(function ($query) use ($product) {
                $query->search($product->title);
                //$query->where('title', 'like', '%' . $product->title . '%');
            })
            ->paginate(8);

        if ($pagination) {
            if (Agent::isDesktop()) {
                return response()->json([
                    'body' => view('related-products', compact('related_products', 'product'))->render()
                ]);
            } else {
                return response()->json([
                    'body' => view('mobile.related-products', compact('related_products', 'product'))->render()
                ]);
            }
        } else {
            return $related_products;
        }
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->has('images')
            ->firstOrFail();

        $url = '/produto/' . $product->slug;

        $product_rating = ProductRating::select(DB::raw('ROUND((SUM(rating) / COUNT(id)), 1) as rating, COUNT(id) as rating_number'))
            ->where('product_id', $product->id)
            ->first();

        $more_colors = Product::whereNotNull('related')
            ->has('images')
            ->where('related', $product->related)
            ->where('id', '!=', $product->id)
            ->get();

        $related_products = $this->relatedProducts($product);

        if (Auth::guard('client')->check()) {
            $client_rating = ProductRating::where('client_id', Auth::guard('client')->user()->id)->where('product_id', $product->id)->first();
        }

        $header_title = 'Clique para pedir | Frete ' . ($product->free_freight ? 'grátis' : 'R$5,00') . ' | Entrega em 24hs | Pague somente ao receber';
        $header_desc = $product->store->name . ' | ' . $product->store->city->title . ' | ' . $product->title;
        $header_canonical = route(\Request::route()->getName(), $product->slug);
        $header_image = url('/uploads/' . $product->store->id . '/products/' . _socialImage($product->images->first()->image));

        if (!file_exists($header_image)) {
            $header_image = url('/uploads/' . $product->store->id . '/products/' . _originalImage($product->images->first()->image));
        }

        for ($i = 1; $i <= $product->store->max_product_unit; $i++) {
            $qtd[$i] = $i;
        }

        if (Agent::isDesktop()) {
            if(\Request::ajax()) {
                return response()->json([
                    'body' => view('show-product', compact('product', 'more_colors', 'related_products', 'product_rating', 'client_rating', 'qtd'))->render(),
                    'header_title' => $header_title,
                    'url' => $url
                ]);
            } else {
                session()->flash('session_flash_product_url', $url);

                return redirect()->route('home');
            }
        } else {
            return view('mobile.show-product', compact('product', 'more_colors', 'related_products', 'product_rating', 'client_rating', 'header_title', 'header_desc', 'header_canonical', 'header_image', 'qtd'));
        }
    }

    public function rating(Request $request)
    {
        $client_id = Auth::guard('client')->user()->id;

        ProductRating::where('client_id', $client_id)->where('product_id', $request->product_id)->delete();

        $rating = ProductRating::create([
            'product_id' => $request->product_id,
            'client_id' => $client_id,
            'rating' => $request->rating,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if($rating) {
            $return['msg'] = 'Avaliação realizada com sucesso!';
        } else {
            $return['msg'] = 'Ocorreu um erro inesperado. Por favor, atualize a página e tente novamente.';
        }

        return json_encode($return);
    }

    public function formSearch(Request $request)
    {
        $city = Cookie::get('city_slug');
        $state = Cookie::get('state_letter_lc');
        $request->keyword = urlencode($request->keyword);

        return redirect()->action('ProductController@search', [$city, $state, $request]);
    }

    public function search($city, $state, Request $request)
    {
        $search_gender = $request->gender ?? null;
        $search_order = $request->order ?? null;
        $keyword = $request->keyword ?? null;
        $advanced = $request->advanced ?? null;
        $search_max_price = $request->max_price ?? null;
        $search_min_price = $request->min_price ?? null;

        $products = Product::has('images')
            ->filterGender($search_gender)
            ->filterOrder($search_order)
            ->filterMinPrice($search_min_price)
            ->filterMaxPrice($search_max_price);

        if ($keyword) {
            //$keyword = urldecode($keyword);

            // SEO
            $header_title = $keyword . ' em ' . Cookie::get('city_title') . ' - ' . Cookie::get('state_letter') . ' | naslojas.com';
            $header_desc = 'Clique para ver ' . $keyword . ' em ' . Cookie::get('city_title') . ' - ' . Cookie::get('state_letter');

            if ($advanced == 'true') {
                $products = $products->where(function ($query) use ($keyword) {
                    $query->search($keyword);
                });

                if ($keyword == 'estilo') {
                    $terms = ['sapato', 'calcado', 'salto alto', 'sapatenis', 'casual', 'colete', 'scarpin', 'jeans', 'sapatilha', 'sandalia', 'calca jeans', 'peep toe', 'bota', 'saia', 'mini saia', 'short', 'bermuda', 'calca', 'vestido', 'blusa', 'camisa', 'camiseta', 'casaco', 'jaqueta', 'blusao', 'moletom', 'moleton', 'agasalho', 'blusinha', 'sobretudo', 'mala', 'mochila', 'bolsa', 'joia', 'relogio', 'anel', 'chapeu', 'manta', 'maleta', 'carteira', 'bikini', 'biquini', 'luva', 'meia', 'carpim', 'bone', 'tiara', 'brinco', 'pochete', 'colar', 'pulseira', 'oculos', 'oculos de sol', 'oculos escuros', 'maquiagem', 'batom', 'tornozeleira', 'cinto', 'suspensorio'];
                } else if ($keyword == 'esporte') {
                    $terms = ['nike', 'adidas', 'olympikus', 'mizuno', 'asics', 'bola', 'esporte', 'gremio', 'inter', 'time', 'legging', 'leging', 'legin', 'penalty', 'topper', 'futebol', 'tennis', 'tenis', 'basquete', 'basket', 'volei', 'corrida', 'academia', 'treino', 'regata', 'camiseta regata', 'calcao', 'moletom', 'moleton', 'meiao', 'sunga', 'maio', 'caneleira', 'joelheira', 'cotoveleira'];
                } else if ($keyword == 'casual') {
                    $terms = ['sapato', 'calcado', 'salto alto', 'sapatenis', 'casual', 'colete', 'scarpin', 'jeans', 'sapatilha', 'sandalia', 'calca jeans', 'peep toe', 'bota', 'saia', 'mini saia', 'short', 'bermuda', 'calca', 'vestido', 'blusa', 'camisa', 'camiseta', 'casaco', 'jaqueta', 'blusao', 'moletom', 'moleton', 'agasalho', 'blusinha', 'sobretudo'];
                } else if ($keyword == 'acessorios') {
                    $terms = ['mala', 'mochila', 'bolsa', 'joia', 'relogio', 'anel', 'chapeu', 'manta', 'maleta', 'carteira', 'bikini', 'biquini', 'luva', 'meia', 'carpim', 'bone', 'tiara', 'brinco', 'pochete', 'colar', 'pulseira', 'oculos', 'oculos de sol', 'oculos escuros', 'maquiagem', 'batom', 'tornozeleira', 'cinto', 'suspensorio'];
                }

                foreach ($terms as $t) {
                    $products = $products->orWhere(function ($q) use ($t) {
                        $q->search($t);
                    });
                }
            } else {
                $products = $products->where(function ($query) use ($keyword) {
                    $query->search($keyword)
                        ->orWhereHas('store', function ($query) use ($keyword) {
                            $query->search($keyword);
                        });
                });
            }
        }

        $products = $products->paginate(30);

        if ($keyword && $products->count() == 0) {
            $products = Product::has('images')
                ->filterGender($search_gender)
                ->filterOrder($search_order)
                ->filterMinPrice($search_min_price)
                ->filterMaxPrice($search_max_price)
                ->where(function ($query) use ($keyword) {
                    $query->search(preg_replace('{(.)\1+}','$1', $keyword))->orWhereHas('store', function ($query) use ($keyword) {
                        $query->search(preg_replace('{(.)\1+}','$1', $keyword));
                    });
                })
                ->paginate(30);
        }

        if (Agent::isDesktop()) {
            return view('search', compact('products', 'keyword', 'search_gender', 'search_order', 'header_title', 'header_desc'));
        } else {
            return view('mobile.search', compact('products', 'keyword', 'search_gender', 'search_order', 'header_title', 'header_desc'));
        }
    }

    public function formSearchAdmin(Request $request)
    {
        return redirect()->action('ProductController@edit', [urlencode($request->keyword)]);
    }

    public function edit($keyword = null)
    {
        $products = Product::withoutGlobalScopes(['active', 'active-store'])
            ->where('store_id', $this->store_id)
            ->where('status', '!=', 2);

        if ($keyword) {
            $keyword = urldecode($keyword);

            $products = $products->where(function ($query) use ($keyword) {
                $query->search($keyword);
                //$query->where('title', 'like', '%' . $keyword . '%')
                    //->orWhere('identifier', $keyword);
            });
        }

        $products = $products->leftJoin(DB::raw("(SELECT RELATED, MAX(ID) MAX_ID FROM products WHERE RELATED IS NOT NULL GROUP BY RELATED) AS P2"), 'P2.related', 'products.related')
            ->orderByRaw('CASE WHEN P2.related IS NULL THEN products.id ELSE max_id END DESC')
            ->orderBy('products.id', 'DESC')
            ->paginate(30);

        $section = 'edit';
        $header_title = 'Editar produtos - naslojas.com';

        if (!\Request::ajax()) {
            if (Agent::isDesktop()) {
                return view('store.product-edit', compact('products', 'section', 'keyword', 'header_title'));
            } else {
                return view('mobile.store.admin-products', compact('products', 'section', 'keyword', 'header_title'));
            }
        } else {
            if (Agent::isDesktop()) {
                return response()->json([
                    'products' => view('store.list-product-edit', compact('products'))->render()
                ]);
            } else {
                return response()->json([
                    'products' => view('mobile.store.list-admin-products', compact('products'))->render()
                ]);
            }
        }
    }

    public function images()
    {
        $products = Product::withoutGlobalScopes(['active', 'active-store'])
            ->where('store_id', $this->store_id)
            ->where('status', 2)
            ->orderBy('id', 'DESC')
            ->paginate(100);

        $section = 'add';

        if (count($products) > 0) {
            $header_title = 'Finalizar cadastro de produtos | naslojas.com';

            if (!\Request::ajax()) {
                return view('store.product-edit', compact('products', 'section', 'header_title'));
            } else {
                return response()->json([
                    'products' => view('store.list-product-edit', compact('products'))->render()
                ]);
            }
        } else {
            $header_title = 'Cadastrar produtos | naslojas.com';

            return view('store.product-images', compact('section', 'header_title'));
        }
    }

    public function uploadImages(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            ['image' => 'image|max:5000'],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        } else {
            $file_name = _uploadImageProduct($request->file('image'), $this->store_id);

            if ($file_name) {
                return Response::json($file_name, 200);
            } else {
                return Response::json('error', 400);
            }
        }
    }

    public function save(Request $request, $id = null)
    {
        $store = Store::find($this->store_id);

        if (Agent::isDesktop()) {
            if ($id) {
                foreach ($request->products as $data) {
                    $request = (object)$data;

                    $validation = Validator::make(
                        $data,
                        $this->rules(),
                        app('App\Http\Controllers\GlobalController')->customMessages()
                    );

                    if ($validation->fails()) {
                        $return['status'] = false;
                        $return['msg'] = $validation->errors()->first();

                        return json_encode($return);
                    } else if (!isset($request->sizes)) {
                        $return['status'] = false;
                        $return['msg'] = 'Informe pelo menos um tamanho';

                        return json_encode($return);
                    } else {
                        $product = Product::withoutGlobalScopes(['active', 'active-store'])
                            ->where('store_id', $this->store_id)
                            ->find($request->product_id);

                        $product->title = $request->title;
                        $product->price = number_format(str_replace(['.', ','], ['', '.'], $request->price), 2, '.', '');
                        $product->gender = $request->gender;
                        $product->off = $request->off ? str_replace('%', '', $request->off) : null;
                        $product->slug = str_slug($product->title, '-');
                        $product->description = $request->description;

                        if ($product->status == 2) {
                            $product->status = 1;

                            if ($store->free_freight_price && $product->price >= $store->free_freight_price) {
                                $product->free_freight = 1;
                            }
                        }

                        // check if slug already exists and add dash in the end
                        $NUM_OF_ATTEMPTS = 10;
                        $attempts = 0;

                        do {
                            try {
                                $product->save();

                                $return['status'] = true;
                                //$return['msg'] = 'Alterações salvas com sucesso!';
                                session()->flash('session_flash_alert', 'Alterações realizadas com sucesso!');
                            } catch(\Exception $e) {
                                $attempts++;

                                if ($attempts >= $NUM_OF_ATTEMPTS) {
                                    $return['status'] = false;
                                    $return['msg'] = 'Ocorreu um erro inesperado. Por favor, atualize a página e tente novamente.';
                                }

                                sleep(rand(0, 10) / 10);

                                $product->slug .= '-' . uniqid();

                                continue;
                            }

                            break;
                        } while ($attempts < $NUM_OF_ATTEMPTS);

                       ProductSize::where('product_id', $product->id)->delete();
                       if (isset($request->sizes)) {
                           foreach ($request->sizes as $size) {
                               $product->sizes()->create(['size' => $size]);
                           }
                       }

                       if (isset($request->images_remove)) {
                           foreach ($request->images_remove as $image_remove) {
                               $this->deleteImages($image_remove);
                           }
                       }

                       if (isset($request->images)) {
                           $key_image = 0;

                           foreach ($request->images as $image) {
                               if(!empty($image)) {
                                   $image_name = _uploadImageProduct($image, $this->store_id);

                                   foreach ($request->images_position as $key_position => $image_position) {
                                        if ($key_position == $key_image) {
                                            $position = $image_position;
                                        }
                                    }

                                   $product->images()->create([
                                       'image' => $image_name,
                                       'position' => $position ?? '0'
                                   ]);

                                   $key_image++;
                               }
                           }
                       }
                    }
                }

                return json_encode($return);
            } else {
                foreach ($request->images as $index) {
                    $product = new Product;

                    $product->store_id = $this->store_id;
                    $product->status = 2;
                    $product->identifier = mt_rand(1000000000, 9999990000);

                    // Checks if identifier arent in use
                    $NUM_OF_ATTEMPTS = 10;
                    $attempts = 0;

                    do {
                        try {
                            $product->save();
                        } catch(\Exception $e) {
                            $attempts++;

                            sleep(rand(0, 10) / 10);

                            $product->identifier = mt_rand(1000000000, 9999990000);

                            continue;
                        }

                        break;
                    } while ($attempts < $NUM_OF_ATTEMPTS);

                     foreach ($index as $key => $img) {
                        $image = new ProductImage;
                        $image->product_id = $product->id;
                        $image->image = $img;
                        $image->position = $key;
                        $image->save();
                    }
                }

                return redirect()->route('product-images');
            }
        } else {
            $validation = Validator::make(
                $request->all(),
                $this->rules(),
                app('App\Http\Controllers\GlobalController')->customMessages()
            );

            if ($validation->fails()) {
                $return['status'] = false;
                $return['msg'] = $validation->errors()->first();

                return json_encode($return);
            } else if (!isset($request->size)) {
                $return['status'] = false;
                $return['msg'] = 'Informe pelo menos um tamanho';

                return json_encode($return);
            } else {
                $price = number_format(str_replace(['.', ','], ['', '.'], $request->price), 2, '.', '');

                if ($id) {
                    $product = Product::withoutGlobalScopes(['active', 'active-store'])->find($id);
                } else {
                    $product = new Product;

                    $product->store_id = $this->store_id;
                    $product->status = 1;
                    $product->identifier = mt_rand(1000000000, 9999990000);

                    if ($store->free_freight_price && $price >= $store->free_freight_price) {
                        $product->free_freight = 1;
                    }
                }

                $product->title = $request->title;
                $product->price = $price;
                $product->gender = $request->gender;
                $product->off = $request->off ? str_replace('%', '', $request->off) : null;
                $product->slug = str_slug($product->title, '-');
                $product->description = $request->description;

               // check if slug already exists and add dash in the end
                $NUM_OF_ATTEMPTS = 10;
                $attempts = 0;

                do {
                    try {
                        $product->save();

                        $return['status'] = true;
                        //$return['msg'] = 'Alterações salvas com sucesso!';
                        session()->flash('session_flash_alert', 'Alterações realizadas com sucesso!');
                    } catch(\Exception $e) {
                        $attempts++;

                        if ($attempts >= $NUM_OF_ATTEMPTS) {
                            $return['status'] = false;
                            $return['msg'] = 'Ocorreu um erro inesperado. Por favor, atualize a página e tente novamente.';
                        }

                        sleep(rand(0, 10) / 10);

                        $product->slug .= '-' . uniqid();

                        if (!$product->id) {
                            $product->identifier = mt_rand(1000000000, 9999990000);
                        }

                        continue;
                    }

                    break;
                } while ($attempts < $NUM_OF_ATTEMPTS);

               ProductSize::where('product_id', $product->id)->delete();
               if (isset($request->size)) {
                   foreach ($request->size as $size) {
                       $product->sizes()->create(['size' => $size]);
                   }
               }

               if (isset($request->image_remove)) {
                   foreach ($request->image_remove as $image_remove) {
                       $this->deleteImages($image_remove);
                   }
               }

               if (isset($request->image)) {
                   $key_image = 0;

                   foreach ($request->image as $image) {
                       if (!empty($image)) {
                           $image_name = _uploadImageProduct($image, $this->store_id);

                           foreach ($request->image_position as $key_position => $image_position) {
                                if ($key_position == $key_image) {
                                    $position = $image_position;
                                }
                            }

                           $product->images()->create([
                               'image' => $image_name,
                               'position' => $position ?? '0'
                           ]);

                           $key_image++;
                       }
                   }
               }

               return json_encode($return);
            }
        }
    }

    public function saveExcel(Request $request)
    {
        $file_name = $request->file->getClientOriginalName();
        $request->file->move(public_path(), $file_name);
        $json = json_decode(file_get_contents(public_path() . '/' . $file_name), true);

        $last_json_id = null;
        $last_naslojas_id = null;
        $last_link = null;

        foreach ($json as $j) {
            if ($j['status'] == '1') {
                if ($last_json_id == $j['id'] && $last_link == $j['link']) {
                    $p = Product::withoutGlobalScopes(['active', 'active-store'])->find($last_naslojas_id);
                    $p->sizes()->create(['size' => $j['tamanho']]);
                } else {
                    $product = new Product;
                    $product->store_id = $this->store_id;
                    $product->status = 2;
                    $product->identifier = mt_rand(1000000000, 9999990000);
                    $product->title = $j['titulo_produto'];
                    $product->slug = str_slug($product->title, '-');
                    $product->price = number_format(($j['vlr_atual']/100), 2);
                    $product->description = $j['descricao_produto'];

                    if ($j['genero'] == '2') {
                        $product->gender = 2;
                    } else if ($j['genero'] == '1') {
                        $product->gender = 3;
                    } else {
                        $product->gender = 1;
                    }

                    // Variation
                    if (!isset($variation) || $last_json_id != $j['id']) {
                        $variation = mt_rand(1000000000, 9999990000);
                    }
                    $product->related = $variation;
                    $last_json_id = $j['id'];
                    $last_link = $j['link'];

                    // Checks if identifier arent in use
                    $NUM_OF_ATTEMPTS = 10;
                    $attempts = 0;

                    do {
                        try {
                            $product->save();
                        } catch(\Exception $e) {
                            $attempts++;

                            sleep(rand(0, 10) / 10);

                            $product->slug .= '-' . uniqid();
                            $product->identifier = mt_rand(1000000000, 9999990000);

                            continue;
                        }

                        break;
                    } while ($attempts < $NUM_OF_ATTEMPTS);

                    $last_naslojas_id = $product->id;

                    if (!$j['tamanho'] || $j['tamanho'] == 'Unico') {
                        $product->sizes()->create(['size' => 'Ú']);
                    } else {
                        if ($j['tamanho'] == '2G') {
                            $size = 'GG';
                        } else if ($j['tamanho'] == '3G') {
                            $size = 'XG';
                        } else {
                            $size = $j['tamanho'];
                        }

                        $product->sizes()->create(['size' => $size]);
                    }

                    $key = 1;
                    foreach (json_decode($j['imagens'], true) as $img) {
                        if ($j['codigo_estoque'] == $img['codigo']) {
                            $image = new ProductImage;
                            $image->product_id = $product->id;
                            $image->image = _uploadImageProduct($img['link'], $this->store_id, false);
                            $image->position = $key;
                            $image->save();

                            $key++;
                        }
                    }
                }
            }
        }

        unlink(public_path() . '/' . $file_name);

        /*$file_name = $request->file->getClientOriginalName();
        $request->file->move(public_path(), $file_name);
        $json = json_decode(file_get_contents(public_path() . '/' . $file_name), true);

        foreach ($json as $j) {
            $sizes = ProductSize::whereHas('product', function ($q) use ($j) {
					$q->where('title', $j['titulo_produto'])
						->withoutGlobalScopes(['active', 'active-store']);
				})
				->where('size', $j['tamanho'])
				->delete();
        }

        Product::doesnthave('sizes')->update(['status' => 0]);

        unlink(public_path() . '/' . $file_name);*/
    }

    public function getCreateEdit($id = null)
    {
        if ($id) {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])->find($id);

            $header_title = 'Editar ' . $product->title . ' | naslojas.com';
        } else {
            $header_title = 'Cadastrar produto | naslojas.com';
        }

        return view('mobile.store.create-edit-product', compact('header_title', 'product'));
    }

    public function deleteImages($image)
    {
        $path = public_path('uploads/' . $this->store_id . '/products/');

        $image_resize_path = $path . $image;
        $image_path = $path . str_replace('_resize', '', $image);

        if(file_exists($image_resize_path)) {
            unlink($image_resize_path);
        }

        if(file_exists($image_path)) {
            unlink($image_path);
        }

        ProductImage::where('image', $image)->delete();
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        if (is_array($id)) {
            foreach ($id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $this->store_id)
                    ->where('id', $i)
                    ->delete();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $this->store_id)
                ->where('id', $id)
                ->delete();
        }

        if ($product) {
            $return['status'] = true;

            // Verify and set to null if exists just one related product
            $this->verifyVariation();
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function enable(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $this->store_id)
                    ->where('id', $i)
                    ->first();

                $product->status = 1;
                $save = $product->save();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $this->store_id)
                ->where('id', $request->id)
                ->first();

            $product->status = 1;
            $save = $product->save();
        }

        if ($save) {
            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function disable(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $this->store_id)
                    ->where('id', $i)
                    ->first();

                $product->status = 0;
                $save = $product->save();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $this->store_id)
                ->where('id', $request->id)
                ->first();

            $product->status = 0;
            $save = $product->save();
        }

        if ($save) {
            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function freeFreight(Request $request)
    {
        $product = Product::withoutGlobalScopes(['active', 'active-store'])
            ->where('store_id', $this->store_id)
            ->where('id', $request->id)
            ->first();

        if ($request->free_freight == 0 && $product->price >= $product->store->free_freight_price) {
            $return['status'] = false;
            $return['msg'] = 'Não é possível desmarcar o frete grátis neste produto, pois o preço dele é maior que o valor de compra mínima, indicado nas configurações, para oferecer frete grátis.';
        } else {
            $product->free_freight = $request->free_freight;

            if ($product->save()) {
                $return['status'] = true;
                $return['msg'] = 'Este produto será oferecido com frete grátis, pois o preço dele é maior que o valor de compra mínima, indicado nas configurações, para oferecer frete grátis.';
            } else {
                $return['status'] = false;
                $return['msg'] = 'Ocorreu um erro inesperado. Atualize a página e tente novamente.';
            }
        }

        return json_encode($return);
    }

    public function colorVariation(Request $request)
    {
        foreach ($request->ids as $id) {
            $save = Product::withoutGlobalScopes(['active', 'active-store'])->find($id)->update(['related' => $request->variation]);
        }

        $return['status'] = $save ? true : false;

        // Verify and set to null if exists a unique related product
        $this->verifyVariation();

        return json_encode($return);
    }

    public function verifyVariation()
    {
        $relateds = Product::withoutGlobalScopes(['active', 'active-store'])
            ->select('related')
            ->groupBy('related')
            ->havingRaw('count(*) = 1')
            ->where('store_id', $this->store_id)
            ->get();

        foreach ($relateds as $related) {
            Product::withoutGlobalScopes(['active', 'active-store'])
                ->select('id')
                ->where('related', $related->related)
                ->where('store_id', $this->store_id)
                ->first()
                ->update(['related' => null]);
        }
    }

    private function rules()
    {
        return [
            'title' => 'required|max:255',
            'price' => 'required',
            'gender' => 'required',
            'description' => 'max:2000'
        ];
    }
}
