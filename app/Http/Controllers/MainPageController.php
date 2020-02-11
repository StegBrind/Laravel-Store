<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class MainPageController extends Controller
{
    public function post(Request $request)
    {
        if ($request->submit == 'RU' || $request->submit == 'EN')
        {
            Cookie::queue('l', strtolower($request->submit), 1440);
        }
        else if (Auth::check())
        {
            Auth::logout();
        }
        return redirect(url('/'));
    }
}
