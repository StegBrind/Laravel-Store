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
                DB::table('stats')->where('date', date('Y-m-d'))->increment('users_count');

                $table = \Sheets::spreadsheet('1WwgxOzrjb8X53LgH30yfVYTJqQCJWvb9bhiuHNoxq1c')->
                sheet('Посещаймость')->all();
                ++$table[count($table) - 1][1]; //increment count of users in cell

                \Sheets::spreadsheet('1WwgxOzrjb8X53LgH30yfVYTJqQCJWvb9bhiuHNoxq1c')->sheet('Посещаймость')->
                    update($table);
        }
        return $next($request);
    }
}
