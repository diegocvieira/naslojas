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
