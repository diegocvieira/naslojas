<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Agent;
use App\City;
use App\District;
use App\State;
use Cache;
use Cookie;

class CityController extends Controller
{
    public function set($id)
    {
        $city = City::whereHas('stores', function ($query) {
                $query->isActive();
            })
            ->with('state')
            ->find($id);

        if (!$city) {
            session()->flash('session_flash_alert', 'Desculpe, ainda não estamos trabalhando na sua cidade.');
            return redirect()->route('home');
        }

        Cookie::queue('city_id', $city->id, '525600');
        Cookie::queue('city_name', $city->title, '525600');
        Cookie::queue('city_slug', $city->slug, '525600');
        Cookie::queue('state_letter', $city->state->letter, '525600');

        return redirect()->route('home');
    }
}
