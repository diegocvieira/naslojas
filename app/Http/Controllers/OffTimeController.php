<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OffTime;

class OffTimeController extends Controller
{
    public function create(Request $request)
    {
        Offtime::where('product_id', $request->product_id)->delete();

        $off = OffTime::create([
            'off' => str_replace('%', '', $request->offtime_off),
            'time' => $request->offtime_time,
            'product_id' => $request->product_id
        ]);

        if ($off) {
            $return['status'] = true;
            $return['id'] = $off->id;
        } else {
            $return['status'] = false;
        }

        return json_encode($return);
    }

    public function remove(Request $request)
    {
        $off = Offtime::where('id', $request->id)->delete();

        $return['status'] = $off ? true : false;

        return json_encode($return);
    }
}
