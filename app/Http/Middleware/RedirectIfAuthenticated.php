<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            
            switch (auth()->user()->is_admin) {
                case '1':
                    return redirect(
                        route('index')
                    );
                    break;
                case '2':
                     return redirect('dashboard/owner');
                    break;
                case '3':
                     return redirect('dashboard/employe');
                    break;    
                default:
                       return redirect(
                           route('/')
                       );
                    break;
            }
        }

        return $next($request);
    }
}
