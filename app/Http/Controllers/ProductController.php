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
                    $query->where('title', 'LIKE', '%' . $keyword_each . '%')->orWhereHas('store', function ($query) use ($keyword_each) {
                        $query->where('name', 'LIKE', '%' . $keyword_each . '%');
                    });
                });
            }
        }

        $products = $products->paginate(20);

        return view('home', compact('products', 'keyword', 'search_gender', 'search_order', 'header_title', 'header_desc'));
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
            $keyword = str_replace('-', ' ', $keyword);

            // separa cada palavra
            $keyword_array = explode(' ', $keyword);

            // se houver mais de 2 palavras e a palavra tiver menos de 4 letras ignora na busca
            foreach ($keyword_array as $keyword_each) {
                if (count($keyword_array) > 2 && strlen($keyword) < 4) {
                    continue;
                }

                $products = $products->where(function ($query) use ($keyword_each) {
                    $query->where('title', 'LIKE', '%' . $keyword_each . '%')
                        ->orWhereHas('store', function ($query) use ($keyword_each) {
                            $query->where('name', 'LIKE', '%' . $keyword_each . '%');
                        });
                });
            }
        }

        $products = $products->orderBy('id', 'DESC')->paginate(20);

        $section = 'edit';

        $header_title = 'Editar produtos - naslojas.com';

        return view('store.product-edit', compact('products', 'section', 'keyword', 'header_title'));
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
            $header_title = 'Finalizar cadastro de produtos - naslojas.com';

            return view('store.product-edit', compact('products', 'section', 'header_title'));
        } else {
            $header_title = 'Cadastrar produtos - naslojas.com';

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
        if ($id) {
            $validation = Validator::make(
                $request->all(),
                ['title' => 'required|max:255', 'price' => 'required', 'gender' => 'required', 'description' => 'max:2000'],
                app('App\Http\Controllers\GlobalController')->customMessages()
            );

            if ($validation->fails()) {
                $return['status'] = false;
                $return['msg'] = $validation->errors()->first();
            } else {
                $product = Product::withoutGlobalScopes(['active', 'active-store'])->find($id);

                if ($product->status == 2) {
                    $product->status = 1;
                }

                $product->title = $request->title;
                $product->price = number_format(str_replace(['.', ','], ['', '.'], $request->price), 2, '.', '');
                $product->installment = $request->installment;
                $product->gender = $request->gender;
                $product->old_price = $request->old_price ? number_format(str_replace(['.', ','], ['', '.'], $request->old_price), 2, '.', '') : null;
                $product->installment_price = $request->installment_price ? number_format(str_replace(['.', ','], ['', '.'], $request->installment_price), 2, '.', '') : null;
                $product->slug = str_slug($product->title, '-');
                $product->description = $request->description;
                $product->related = $request->related;

               // check if slug already exists and add dash in the end
                $NUM_OF_ATTEMPTS = 10;
                $attempts = 0;

                do {
                    try {
                        $product->save();

                        $return['status'] = true;
                        $return['msg'] = 'Alterações salvas com sucesso!';
                    } catch(\Exception $e) {
                        $attempts++;

                        if($attempts >= $NUM_OF_ATTEMPTS) {
                            $return['status'] = false;
                            $return['msg'] = 'Ocorreu um erro inesperado. Por favor, atualize a página e tente novamente.';
                        }

                        sleep(rand(0, 10) / 10);

                        $product->slug = str_slug($product->title, '-') . '-' . RAND(111111, 999999);

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
                       if(!empty($image)) {
                           foreach ($request->image_position as $key_position => $image_position) {
                                if ($key_position == $key_image) {
                                    $position = $image_position;
                                }
                            }

                           $product->images()->create([
                               'image' => _uploadImage($image),
                               'position' => $position ?? '0'
                           ]);

                           $key_image++;
                       }
                   }
               }
            }

            return json_encode($return);
        } else {
            foreach ($request->images as $index) {
                $product = new Product;
                $product->store_id = Auth::guard('store')->user()->store_id;

                $product->status = 2;
                $product->save();

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

    public function delete($id)
    {
        $product = Product::withoutGlobalScopes(['active', 'active-store'])->find($id);

        foreach($product->images as $image) {
            $this->deleteImages($image->image);
        }

        $product->delete();

        return json_encode(true);
    }

    public function enableDisable($id)
    {
        $product = Product::withoutGlobalScopes(['active', 'active-store'])->find($id);
        $product->status = $product->status == 0 ? 1 : 0;
        $product->save();

        return json_encode(true);
    }
}
