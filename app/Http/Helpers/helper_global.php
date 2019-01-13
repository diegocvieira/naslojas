<?php
function _setCity($city, $force = false)
{
    if ($city != null && ($force == true || !Cookie::get('city_slug'))) {
        Cookie::queue('city_id', $city->id, '525600');
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

function _reservePrice($price, $discount)
{
    return round($price - ($price * ($discount / 100)), 2);
}

function _taxes($parcel_qtd, $parcel_price, $product_price)
{
    if (round($parcel_qtd * $parcel_price) == round($product_price)) {
        return 'sem juros';
    } else {
        return false;
    }
}

function _originalImage($image)
{
    return str_replace('_resize', '', $image);
}

function _dateFormat($date)
{
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

    $date = date('Y-m-d', strtotime($date));

    if($date == date('Y-m-d')) {
        $date_format = 'HOJE';
    } else if($date == date('Y-m-d', strtotime('-1 day'))) {
        $date_format = 'ONTEM';
    } else {
        if(date('Y-m-d', strtotime('-7 day')) > $date) {
            $diff = date_diff(date_create_from_format('Y-m-d', $date), date_create());

            if(date('Y-m-d', strtotime('-30 day')) > $date && date('Y-m-d', strtotime('-365 day')) < $date) {
                $date_format = 'Há ' . $diff->m . ($diff->m > 1 ? ' meses' : ' mês');
            } else {
                $date_format = 'Há ' . $diff->y . ($diff->y > 1 ? ' anos' : ' ano');
            }
        } else {
            $date_format = strftime('%A', strtotime($date));

            if($date_format != 'sábado' && $date_format != 'domingo') {
                $date_format = $date_format . '-feira';
            }
        }
    }

    return $date_format;
}

function _uploadImage($file)
{
    $microtime = microtime(true) . RAND(111111, 999999);

    $images = [
        '248' => $microtime . '_resize.jpg',
        '600' => $microtime . '.jpg'
    ];

    foreach($images as $size => $image_name) {
        $image = new \Imagick($file->path());

        if ($image->getImageAlphaChannel()) {
            $image->setImageAlphaChannel(11);
        }

        $image->setImageBackgroundColor('#ffffff');
        $image->setColorspace(\Imagick::COLORSPACE_SRGB);
        $image->setImageFormat('jpg');
        $image->stripImage();
        $image->setImageCompressionQuality(75);
        $image->setSamplingFactors(array('2x2', '1x1', '1x1'));
        $image->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
        $image->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
        $image->cropThumbnailImage($size, $size);
        //$image->autoOrient();

        switch ($image->getImageOrientation()) {
        case \Imagick::ORIENTATION_TOPLEFT:
            break;
        case \Imagick::ORIENTATION_TOPRIGHT:
            $image->flopImage();
            break;
        case \Imagick::ORIENTATION_BOTTOMRIGHT:
            $image->rotateImage("#fff", 180);
            break;
        case \Imagick::ORIENTATION_BOTTOMLEFT:
            $image->flopImage();
            $image->rotateImage("#fff", 180);
            break;
        case \Imagick::ORIENTATION_LEFTTOP:
            $image->flopImage();
            $image->rotateImage("#fff", -90);
            break;
        case \Imagick::ORIENTATION_RIGHTTOP:
            $image->rotateImage("#fff", 90);
            break;
        case \Imagick::ORIENTATION_RIGHTBOTTOM:
            $image->flopImage();
            $image->rotateImage("#fff", 90);
            break;
        case \Imagick::ORIENTATION_LEFTBOTTOM:
            $image->rotateImage("#fff", -90);
            break;
        default: // Invalid orientation
            break;
        }
        $image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

        $image->writeImage(public_path('uploads/' . Auth::guard('store')->user()->store_id . '/products/' . $image_name));

        $image->destroy();
    }

    return $images[248];
}
