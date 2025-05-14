<?php

namespace Cmvgroup\SSOAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateWithApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( !auth('api')->user() ){
            return redirect()
                ->away( config('sso-auth.sso_url')
                    . config('sso-auth.login_page', '/') 
                    . '?callback=' . urlencode(request()->fullUrl()));
        }

        return $next($request);
    }
}