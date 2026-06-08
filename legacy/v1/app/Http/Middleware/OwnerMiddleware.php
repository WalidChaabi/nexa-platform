<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class OwnerMiddleware
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
        if(Auth::user() &&  Auth::user()->is_admin ==2)
        {
            return $next($request);

        }else{
            return route('authentication.login');
        }
    }
}
