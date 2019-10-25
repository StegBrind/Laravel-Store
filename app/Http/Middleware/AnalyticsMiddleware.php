<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('last_activity_date') ||
            $request->session()->get('last_activity_date') != date('Y-m-d'))
        {
                $request->session()->put('last_activity_date', date('Y-m-d'));
                $request->session()->save();
                DB::table('stats')->where('date', date('Y-m-d'))->increment('users_count');
        }
        return $next($request);
    }
}
