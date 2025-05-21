<?php

namespace Cmvgroup\SSOAuth\Http\Controllers;

use App\Classes\Encryption;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Cmvgroup\SSOAuth\Classes\ApiCookie;

class AuthController extends Controller
{
    public function init(Request $request) 
    {
        if ( !$request->has('token')) {
            return response()->json(['error' => 'Token not found'], 401);
        }
    
        $token = $request->input('token');
        $path = $request->path ?? '/';

        $cookie = ApiCookie::make('auth_token', $token, config('session.lifetime', 120));

        return redirect($path)
            ->withCookie($cookie);
    }

    public function login()
    {
        return redirect()
                ->away( config('sso-auth.sso_url')
                    . config('sso-auth.login_page', '/') );
    }

    public function logout(Request $request)
    {
        $token = request()->cookie('auth_token');
        Cache::forget('auth_user_' . md5($token));

        
        ApiCookie::expire('auth_token');
       
        return back();
    }
     
}
