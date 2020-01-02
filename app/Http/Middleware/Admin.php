<?php

namespace App\Http\Middleware;

use Closure;
use KodiCMS\Assets\Facades\Meta;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Meta::removeJs('admin-default');
        return \App\Admin::tryLoginByCookies() ? $next($request) : redirect('admin');
    }
}
