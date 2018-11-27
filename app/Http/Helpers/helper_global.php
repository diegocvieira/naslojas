<?php
function _setCity($city, $force = false)
{
    if ($city != null && ($force == true || !Cookie::get('city_slug'))) {
        //Cookie::queue('city_id', $city->id, '525600');
        Cookie::queue('city_title', $city->title, '525600');
        Cookie::queue('city_slug', $city->slug, '525600');

        //Cookie::queue('state_id', $city->state->id, '525600');
        //Cookie::queue('state_title', $city->state->title, '525600');
        //Cookie::queue('state_slug', $city->state->slug, '525600');
        Cookie::queue('state_letter', $city->state->letter, '525600');
        Cookie::queue('state_letter_lc', $city->state->letter_lc, '525600');
    }
}

function _discount($price, $old_price)
{
	if ($old_price != 0.00) {
		return str_replace('-', '', round(($price / $old_price - 1) * 100));
	} else {
		return false;
	}
}

function _taxes($parcel_qtd, $parcel_price, $product_price)
{
    if (round($parcel_qtd * $parcel_price) == round($product_price)) {
        return 'sem juros';
    } else {
        return false;
    }
}

function _uploadImage($file, $old_file)
{
    $path = public_path('uploads/' . Auth::guard('store')->user()->store_id . '/products');
    $microtime = microtime(true);

    $images = [
        '248' => $microtime . '_resize.jpg',
        '600' => $microtime . '.jpg'
    ];

    // Remove old images
    if($old_file) {
        $old_image_resize = $path . '/' . $old_file;
        $old_image = $path . '/' . str_replace('_resize', '', $old_file);

        if(file_exists($old_image_resize)) {
            unlink($old_image_resize);
        }

        if(file_exists($old_image)) {
            unlink($old_image);
        }
    }

    foreach($images as $size => $image_name) {
        $image = new \Imagick($file->path());

        $image->setColorspace(\Imagick::COLORSPACE_SRGB);
        $image->setImageFormat('jpg');
        $image->stripImage();
        $image->setImageCompressionQuality(75);
        $image->setSamplingFactors(array('2x2', '1x1', '1x1'));
        $image->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
        //$image->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
        $image->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
        $image->cropThumbnailImage($size, $size);
        $image->writeImage($path . '/' . $image_name);

        $image->destroy();
    }

    return $images[248];
}
