<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Agent;
use App\City;
use App\District;
use App\State;
use Cache;
use Cookie;

class LocationController extends Controller
{
    public function index()
    {
        // $cities = Cache::rememberForever('cities121', function () {
            $cities = City::with(['state' => function ($query) {
                    $query->select('id', 'letter');
                }])
                ->orderBy('title', 'asc')
                ->select('id', 'title', 'state_id')
                ->get();
        // });

        $districts = Cache::rememberForever('districts', function () {
            return District::orderBy('name', 'ASC')->get();
        });

        if (Agent::isMobile()) {
            return view('mobile.home');
        } else {
            return view('home', compact('cities', 'districts'));
        }
    }

    public function search(Request $request)
    {

    }

    public function getDistricts(Request $request)
    {
        $districts = Districts::where('city_id', $request->city_id)
            ->orderBy('name', 'ASC')
            ->get();

        return request()->json($districts);
    }

    public function set(Request $request)
    {
        $city = City::whereHas('stores', function ($query) {
                $query->isActive();
            })
            ->with('state')
            ->find($request->city_id);

        if (!$city) {
            session()->flash('session_flash_alert', 'Desculpe, ainda não estamos entregando na sua cidade.');
            return redirect()->route('home');
        }

        $district = District::whereHas('city.stores', function ($query) use ($request) {
                $query->isActive()
                    ->whereHas('freights', function($query) use ($request) {
                        $query->where('district_id', $request->district_id);
                    });
            })
            ->find($request->district_id);

        if (!$district) {
            session()->flash('session_flash_alert', 'Desculpe, ainda não estamos entregando no seu bairro.');
            return redirect()->route('home');
        }

        Cookie::queue('city_id', $city->id, '525600');
        Cookie::queue('city_name', $city->title, '525600');
        Cookie::queue('city_slug', $city->slug, '525600');
        Cookie::queue('state_letter', $city->state->letter, '525600');
        Cookie::queue('district_id', $district->id, '525600');
        Cookie::queue('district_name', $district->name, '525600');
        Cookie::queue('district_slug', $district->slug, '525600');

        return redirect()->route('store.index');
    }
}
