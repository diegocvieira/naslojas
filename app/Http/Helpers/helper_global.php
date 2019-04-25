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

function _oldPrice($price, $off)
{
	if ($off) {
        return ($price * 100) / (100 - $off);
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

function _uploadImageProduct($file, $store_id)
{
    $microtime = microtime(true) . RAND(111111, 999999);

    $images = [
        '248' => $microtime . '_resize.jpg',
        '900' => $microtime . '.jpg'
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
        $image->resizeImage($size, $size, \imagick::FILTER_LANCZOS, 1, TRUE);
        //$image->cropThumbnailImage($size, $size);
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

        $image->writeImage(public_path('uploads/' . $store_id . '/products/' . $image_name));

        $image->destroy();
    }

    return $images[248];
}

function _uploadImage($file, $store_id)
{
    $image_name = microtime(true) . RAND(111111, 999999) . '.jpg';

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

    $image->writeImage(public_path('uploads/' . $store_id . '/' . $image_name));

    $image->destroy();

    return $image_name;
}

function _businessDay($date = null, $check = null)
{
    if (!$date) {
        $date = date('Y-m-d');
    }

    $date = date('Y-m-d', strtotime($date . ' + 1 day'));
    $week_day = date('w', strtotime($date));

    if ($week_day == 6 || $week_day == 0) {
        return _businessDay($date, $check);
    } else {
        switch ($week_day) {
            case 1:
                $week = 'segunda-feira';
                break;
            case 2:
                $week = 'terça-feira';
                break;
            case 3:
                $week = 'quarta-feira';
                break;
            case 4:
                $week = 'quinta-feira';
                break;
            case 5:
                $week = 'sexta-feira';
        }

        if ($check) {
            return $date;
        } else {
            return 'Até ' . $week . ' ' . date('d/m/Y', strtotime($date));
        }
    }
}

function _generateParcels($price, $min_parcel_price, $max_parcel)
{
    for ($i = 1; $i <= $max_parcel; $i++) {
        if ($price / $i < $min_parcel_price) {
            return $i - 1;
        }
    }
}

function _paymentMethods()
{
    return [
        '0' => [
            'Dinheiro' => [
                '0' => 'À vista'
            ]
        ],
        '1' => [
            'Cartão de crédito' => [
                '0' => 'Visa',
                '1' => 'MasterCard',
                '2' => 'Elo',
                '3' => 'American Express',
                '4' => 'Dinners Club',
                '5' => 'Hipercard',
                '6' => 'Banricompras',
                '7' => 'VerdeCard',
                '8' => 'Maestro',
                '9' => 'Cabal'
            ]
        ],
        '2' => [
            'Cartão de débito' => [
                '0' => 'Visa',
                '1' => 'MasterCard',
                '2' => 'Elo',
                '3' => 'American Express',
                '4' => 'Dinners Club',
                '5' => 'Hipercard',
                '6' => 'Banricompras',
                '7' => 'VerdeCard',
                '8' => 'Maestro',
                '9' => 'Cabal'
            ]
        ]
    ];
}

function _getPaymentMethod($value)
{
    $value_split = explode('-', $value);

    switch ($value_split[0]) {
        case '0':
            $method = 'Dinheiro';
            break;
        case '1':
            $method = 'Cartão de crédito';
            break;
        case '2':
            $method = 'Cartão de débito';
            break;
        default:
            $method = '';
    }

    if ($method == 'Dinheiro') {
        return $method;
    }

    switch ($value_split[1]) {
        case '0':
            $payment = 'Visa';
            break;
        case '1':
            $payment = 'MasterCard';
            break;
        case '2':
            $payment = 'Elo';
            break;
        case '3':
            $payment = 'American Express';
            break;
        case '4':
            $payment = 'Dinners Club';
            break;
        case '5':
            $payment = 'Hipercard';
            break;
        case '6':
            $payment = 'Banricompras';
            break;
        case '7':
            $payment = 'VerdeCard';
            break;
        case '8':
            $payment = 'Maestro';
            break;
        case '9':
            $payment = 'Cabal';
            break;
        default:
            $payment = '';
    }

    return $method . ' - ' . $payment;
}

function _freights($store_id)
{
    $freights = App\StoreFreight::join('districts', 'district_id', 'districts.id')
        ->where('store_id', $store_id)
        ->orderBy('districts.name', 'ASC')
        ->get();

    return $freights;
}
