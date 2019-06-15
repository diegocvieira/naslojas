<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class PostController extends Controller
{
    public function create(Request $request)
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
}
