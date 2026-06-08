<?php

namespace App\Http\Middleware;

use Closure;

class Active
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
        if(auth()->user()->is_admin != 1 && auth()->user()->is_active == 0 )
        {
              return redirect( route('hold') );
        }
        return $next($request);
    }
}
