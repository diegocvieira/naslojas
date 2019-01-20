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
use DB;
use Agent;

class ProductController extends Controller
{
    public function relatedProducts(Product $product, $pagination = null)
    {
        $related_products = Product::where('id', '!=', $product->id)
            ->whereHas('store', function ($query) use ($product) {
                $query->where('city_id', $product->store->city->id);
            })
            ->where(function ($query) use ($product) {
                //$query->search($product->title);
                $query->where('title', 'like', '%' . $product->title . '%');
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
        $product = Product::where('slug', $slug)->firstOrFail();

        $url = '/produto/' . $product->slug;

        $product_rating = ProductRating::select(DB::raw('ROUND((SUM(rating) / COUNT(id)), 1) as rating, COUNT(id) as rating_number'))
        ->where('product_id', $product->id)
        ->first();

        $more_colors = Product::whereNotNull('related')->where('related', $product->related)->where('id', '!=', $product->id)->get();

        $related_products = $this->relatedProducts($product);

        if (Auth::guard('client')->check()) {
            $client_rating = ProductRating::where('client_id', Auth::guard('client')->user()->id)->where('product_id', $product->id)->first();
        }

        $header_title = $product->title . ' - ' . $product->store->name . ' - ' . $product->store->city->title . ' - ' . $product->store->city->state->letter . ' | naslojas.com';
        $header_desc = 'Clique para ver os detalhes do produto na loja ' . $product->store->name . ' em ' . $product->store->city->title . ' - ' . $product->store->city->state->letter;
        $header_canonical = route(\Request::route()->getName(), $product->slug);
        $header_image = url('/uploads/' . $product->store->id . '/products/' . $product->images->first()->image);

        if (Agent::isDesktop()) {
            if(\Request::ajax()) {
                return response()->json([
                    'body' => view('show-product', compact('product', 'more_colors', 'related_products', 'product_rating', 'client_rating'))->render(),
                    'header_title' => $header_title,
                    'url' => $url
                ]);
            } else {
                session()->flash('session_flash_product_url', $url);

                return redirect()->route('home');
            }
        } else {
            return view('mobile.show-product', compact('product', 'more_colors', 'related_products', 'product_rating', 'client_rating', 'header_title', 'header_desc', 'header_canonical', 'header_image'));
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

            $products = $products->where(function ($query) use ($keyword) {
                /*$query->search($keyword)->orWhereHas('store', function ($query) use ($keyword) {
                    $query->search($keyword);
                });*/
                $query->where('title', 'like', '%' . $keyword . '%')->orWhereHas('store', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }

        $products = $products->paginate(30);

        if ($keyword && $products->count() == 0) {
            $products = Product::filterGender($search_gender)->filterOrder($search_order)
                ->where(function ($query) use ($keyword) {
                    /*$query->search(preg_replace('{(.)\1+}','$1', $keyword))->orWhereHas('store', function ($query) use ($keyword) {
                        $query->search(preg_replace('{(.)\1+}','$1', $keyword));
                    });*/
                    $query->where('title', 'like', '%' . preg_replace('{(.)\1+}','$1', $keyword) . '%')->orWhereHas('store', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . preg_replace('{(.)\1+}','$1', $keyword) . '%');
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
            ->where('store_id', Auth::guard('store')->user()->store_id)
            ->where('status', '!=', 2);

        if ($keyword) {
            $keyword = urldecode($keyword);

            $products = $products->where(function ($query) use ($keyword) {
                //$query->search($keyword);
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('identifier', $keyword);
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
            ->where('store_id', Auth::guard('store')->user()->store_id)
            ->where('status', 2)
            ->orderBy('id', 'DESC')
            ->paginate(999);

        $section = 'add';

        if (count($products) > 0) {
            $header_title = 'Finalizar cadastro de produtos | naslojas.com';

            return view('store.product-edit', compact('products', 'section', 'header_title'));
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
            $file_name = _uploadImage($request->file('image'));

            if ($file_name) {
                return Response::json($file_name, 200);
            } else {
                return Response::json('error', 400);
            }
        }
    }

    public function save(Request $request, $id = null)
    {
        $store_id = Auth::guard('store')->user()->store_id;

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
                            ->where('store_id', $store_id)
                            ->find($request->product_id);

                        if ($product->status == 2) {
                            $product->status = 1;
                        }

                        $product->title = $request->title;
                        $product->price = number_format(str_replace(['.', ','], ['', '.'], $request->price), 2, '.', '');
                        $product->installment = $request->installment ? str_replace('x', '', $request->installment) : null;
                        $product->gender = $request->gender;
                        $product->old_price = $request->old_price ? number_format(str_replace(['.', ','], ['', '.'], $request->old_price), 2, '.', '') : null;
                        $product->installment_price = $request->installment_price ? number_format(str_replace(['.', ','], ['', '.'], $request->installment_price), 2, '.', '') : null;
                        $product->slug = str_slug($product->title, '-');
                        $product->description = $request->description;
                        $product->reserve_discount = $request->reserve_discount ? str_replace('%', '', $request->reserve_discount) : null;

                        // check if slug already exists and add dash in the end
                        $NUM_OF_ATTEMPTS = 10;
                        $attempts = 0;

                        do {
                            try {
                                $product->save();

                                $return['status'] = true;
                                //$return['msg'] = 'Alterações salvas com sucesso!';
                                session()->flash('session_flash_alert', 'Produtos salvos com sucesso!');
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
                                   $image_name = _uploadImage($image);

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

                    $product->store_id = $store_id;
                    $product->status = 2;
                    $product->identifier = mt_rand(1000000000, 9999990000);
                    $product->reserve = Auth::guard('store')->user()->store->reserve;

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
                if ($id) {
                    $product = Product::withoutGlobalScopes(['active', 'active-store'])->find($id);
                } else {
                    $product = new Product;

                    $product->store_id = Auth::guard('store')->user()->store_id;
                    $product->status = 1;
                    $product->identifier = mt_rand(1000000000, 9999990000);
                    $product->reserve = Auth::guard('store')->user()->store->reserve;
                }

                $product->title = $request->title;
                $product->price = number_format(str_replace(['.', ','], ['', '.'], $request->price), 2, '.', '');
                $product->installment = $request->installment ? str_replace('x', '', $request->installment) : null;
                $product->gender = $request->gender;
                $product->old_price = $request->old_price ? number_format(str_replace(['.', ','], ['', '.'], $request->old_price), 2, '.', '') : null;
                $product->installment_price = $request->installment_price ? number_format(str_replace(['.', ','], ['', '.'], $request->installment_price), 2, '.', '') : null;
                $product->slug = str_slug($product->title, '-');
                $product->description = $request->description;
                $product->reserve_discount = $request->reserve_discount ? str_replace('%', '', $request->reserve_discount) : null;

               // check if slug already exists and add dash in the end
                $NUM_OF_ATTEMPTS = 10;
                $attempts = 0;

                do {
                    try {
                        $product->save();

                        $return['status'] = true;
                        //$return['msg'] = 'Alterações salvas com sucesso!';
                        session()->flash('session_flash_alert', 'Produto salvo com sucesso!');
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
                           $image_name = _uploadImage($image);

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
        $path = public_path('uploads/' . Auth::guard('store')->user()->store_id . '/products/');

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
        $store_id = Auth::guard('store')->user()->store_id;
        $id = $request->id;

        if (is_array($id)) {
            foreach ($id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $store_id)
                    ->where('id', $i)
                    ->delete();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $store_id)
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
        $store_id = Auth::guard('store')->user()->store_id;

        if (is_array($request->id)) {
            foreach ($request->id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $store_id)
                    ->where('id', $i)
                    ->first();

                $product->status = 1;
                $save = $product->save();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $store_id)
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
        $store_id = Auth::guard('store')->user()->store_id;

        if (is_array($request->id)) {
            foreach ($request->id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $store_id)
                    ->where('id', $i)
                    ->first();

                $product->status = 0;
                $save = $product->save();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $store_id)
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

    public function reserveEnable(Request $request)
    {
        $store_id = Auth::guard('store')->user()->store_id;

        if (is_array($request->id)) {
            foreach ($request->id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $store_id)
                    ->where('id', $i)
                    ->first();

                $product->reserve = 1;
                $save = $product->save();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $store_id)
                ->where('id', $request->id)
                ->first();

            $product->reserve = 1;
            $save = $product->save();
        }

        if ($save) {
            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function reserveDisable(Request $request)
    {
        $store_id = Auth::guard('store')->user()->store_id;

        if (is_array($request->id)) {
            foreach ($request->id as $i) {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])
                    ->where('store_id', $store_id)
                    ->where('id', $i)
                    ->first();

                $product->reserve = 0;
                $save = $product->save();
            }
        } else {
            $product = Product::withoutGlobalScopes(['active', 'active-store'])
                ->where('store_id', $store_id)
                ->where('id', $request->id)
                ->first();

            $product->reserve = 0;
            $save = $product->save();
        }

        if ($save) {
            $return['status'] = true;
        } else {
            $return['status'] = false;
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
        $store_id = Auth::guard('store')->user()->store_id;

        $relateds = Product::withoutGlobalScopes(['orderby-reserve', 'active', 'active-store'])
            ->select('related')
            ->groupBy('related')
            ->havingRaw('count(*) = 1')
            ->where('store_id', $store_id)
            ->get();

        foreach ($relateds as $related) {
            Product::withoutGlobalScopes(['active', 'active-store'])
                ->select('id')
                ->where('related', $related->related)
                ->where('store_id', $store_id)
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
