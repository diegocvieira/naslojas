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

function _priceOff($price, $off)
{
    return $price - (($off / 100) * $price);
}

function _checkDateOff($date, $time)
{
    $final_date = date('Y-m-d H:i:s', strtotime('+' . $time . ' hours', strtotime($date)));

    if (date('Y-m-d H:i:s') < $final_date) {
        return true;
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

function _socialImage($image)
{
    return str_replace('resize', 'social', $image);
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

function _uploadImageProduct($file, $store_id, $input_file = true)
{
    $microtime = microtime(true) . RAND(111111, 999999);

    $images = [
        '248x248' => $microtime . '_resize.jpg',
        '900x900' => $microtime . '.jpg',
        '540x282' => $microtime . '_social.jpg'
    ];

    foreach($images as $size => $image_name) {
        if ($input_file) {
            $image = new \Imagick($file->path());
        } else {
            if (get_headers($file, 1)[0] == 'HTTP/1.1 200 OK') {
                $image = new \Imagick($file);
            } else {
                return false;
            }
        }

        if ($image->getImageAlphaChannel()) {
            $image->setImageAlphaChannel(11);
        }

        $explode = explode('x', $size);
        $width = $explode[0];
        $height = $explode[1];

        $image->setImageBackgroundColor('#ffffff');
        $image->setColorspace(\Imagick::COLORSPACE_SRGB);
        $image->setImageFormat('jpg');
        $image->stripImage();
        $image->setImageCompressionQuality(75);
        $image->setSamplingFactors(array('2x2', '1x1', '1x1'));
        $image->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
        $image->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

        //if ($width == '248') {
            //$image->cropThumbnailImage($width, $height);
        //} else {
            $image->resizeImage($width, $height, \imagick::FILTER_LANCZOS, 1, TRUE);
        //}

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

    return $images['248x248'];
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

function _filterOrder()
{
    return [
        'populares' => 'Populares',
        'menor_preco' => 'Menor preço',
        'maior_preco' => 'Maior preço'
    ];
}

function _filterGender()
{
    return [
        'unissex' => 'unissex',
        'masculino' => 'masculino',
        'feminino' => 'feminino'
    ];
}

function _filterColor()
{
    return [
        'preto' => 'preto',
        'branco' => 'branco',
        'cinza' => 'cinza',
        'marrom' => 'marrom',
        'bege' => 'bege',
        'azul' => 'azul',
        'azul-claro' => 'azul claro',
        'verde' => 'verde',
        'verde-claro' => 'verde claro',
        'amarelo' => 'amarelo',
        'vermelho' => 'vermelho',
        'laranja' => 'laranja',
        'roxo' => 'roxo',
        'rosa' => 'rosa',
        'lilas' => 'lilás'
    ];
}

function _filterInstallment()
{
    return [
        '3' => '3x',
        '6' => '6x',
        '10' => '10x',
        '12' => '12x',
        '15' => '15x'
    ];
}

function _filterOff()
{
    return [
        '10' => '10%',
        '20' => '20%',
        '30' => '30%',
        '40' => '40%',
        '50' => '50%'
    ];
}

function _filterPrice()
{
    return [
        '0-50.00' => 'até R$50',
        '50.00-75.00' => 'R$50 a R$75',
        '75.00-100.00' => 'R$75 a R$100',
        '100.00-150.00' => 'R$100 a R$150',
        '150.00-200.00' => 'R$150 a R$200',
        '200.00-0' => 'mais de R$200'
    ];
}

function _filterBrand()
{
    return [
        'adidas',
        'all star',
        'animal print',
        'asics',
        'azille',
        'bebecê',
        'beira rio',
        'bibi',
        'bonton',
        'bottero',
        'bull terrier',
        'carrano',
        'cavaliery',
        'chaville',
        'colcci',
        'columbia',
        'comfortflex',
        'confortgel',
        'cravo e canela',
        'cristófoli',
        'crysalis',
        'dakota',
        'divalesi',
        'd moon',
        'doce trama',
        'farm',
        'ferracini',
        'fiever',
        'fila',
        'fleet body',
        'franchini',
        'freeday',
        'fuzulla',
        'gang',
        'grendene',
        'grendha',
        'hering',
        'hocks',
        'invoice',
        'jota pe',
        'kidy',
        'kildare',
        'klin',
        'lacoste',
        'lez a lez',
        'lhombre',
        'malarrara',
        'melissa',
        'mizuno',
        'moikana',
        'moleca',
        'neon',
        'nike',
        'olympikus',
        'orcade',
        'ortopé',
        'osklen',
        'palterm',
        'pampili',
        'pegada',
        'penalty',
        'petite jolie',
        'piccadilly',
        'polo',
        'quiz',
        'ramarim',
        'reserva',
        'sapri',
        'schutz',
        'skechers',
        'sweet chic',
        'thuha',
        'timberland',
        'tommy',
        'topper',
        'umbro',
        'usaflex',
        'uza',
        'valentina',
        'vans',
        'verofatto',
        'via marte',
        'via pina',
        'via uno',
        'vicenza',
        'vizzano',
        'werner',
        'west coast',
        'whoop'
    ];
}

function _filterCategory()
{
    return [
        'agasalho',
        'anel',
        'basquete',
        'batom',
        'bermuda',
        'bikini',
        'biquini',
        'blusa',
        'blusão',
        'bola',
        'bolsa',
        'boné',
        'botas',
        'brinco',
        'calça',
        'calçado',
        'calção',
        'camisa',
        'camiseta',
        'camiseta regata',
        'caneleira',
        'carpim',
        'carteira',
        'casaco',
        'chapéu',
        'chinelos',
        'cinto',
        'colar',
        'colete',
        'cotoveleira',
        'futebol',
        'jaqueta',
        'jeans',
        'joelheira',
        'jóia',
        'legging',
        'luva',
        'maiô',
        'mala',
        'maleta',
        'manta',
        'maquiagem',
        'meia',
        'meião',
        'mini saia',
        'mochila',
        'moletom',
        'mules',
        'óculos',
        'óculos de sol',
        'oxfords',
        'peep toe',
        'pochete',
        'pulseira',
        'regata',
        'relógio',
        'saia',
        'salto alto',
        'sandália',
        'sapatênis',
        'sapatilha',
        'sapato',
        'scarpin',
        'short',
        'sobretudo',
        'sunga',
        'suspensório',
        'tamanco',
        'tênis',
        'tiara',
        'tornozeleira',
        'vestido'
    ];
}
