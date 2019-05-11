<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Newsletter;
use Validator;

class NewsletterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['email' => 'required|email|max:100|unique:newsletter'],
            app('App\Http\Controllers\GlobalController')->customMessages()
        );

        if (!$validator->fails()) {
            Newsletter::create(['email' => $request->email]);
        }

        return json_encode(true);
    }
}
