<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class IsEmployer
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
        if (Auth::user() &&  Auth::user()->is_admin ==3) {

            return $next($request);
        }
        
        return abort(403);
    }
}
