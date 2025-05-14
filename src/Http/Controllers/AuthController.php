<?php

namespace Cmvgroup\SSOAuth\Http\Controllers;

use App\Classes\Encryption;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function init(Request $request) 
    {
        if ( !$request->has('token')) {
            return response()->json(['error' => 'Token not found'], 401);
        }
    
        $token = $request->input('token');
        $path = $request->path ?? '/';

        return redirect($path)
            ->withCookie('auth_token', $token );
    }

    public function logout()
    {
        $token = request()->cookie('auth_token');
        Cache::forget('auth_user_' . md5($token));
       
        return back()->withoutCookie('auth_token');
    }
     
}
