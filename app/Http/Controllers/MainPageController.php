<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class MainPageController extends Controller
{
    public function get()
    {
        return view('index');
    }

    public function post(Request $request)
    {
        if ($request->submit == 'RU' || $request->submit == 'EN')
        {
            Cookie::queue('l', strtolower($request->submit), 1440);
            return redirect(url('/'));
        }
        if (Auth::check())
        {
            Auth::logout();
            return view('index');
        }
    }
}
