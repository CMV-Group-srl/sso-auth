<?php

namespace Cmvgroup\SSOAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cmvgroup\SSOAuth\Classes\ApiCookie;

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

        $response = $next($request);
        if (!method_exists($response,'withCookie'))
            return $response;
        
        $token = request()->cookie('auth_token');
        $cookie = ApiCookie::make('auth_token', $token, config('session.lifetime', 120));

        return  $response->withCookie($cookie);
    }
}