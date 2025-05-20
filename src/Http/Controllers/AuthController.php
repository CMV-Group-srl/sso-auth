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

        // Imposta il dominio base con un punto all'inizio
        $domain = config('session.domain'); // Ottieni dal file config/session.php
        
        // Se il dominio non è configurato, prova a dedurlo dalla richiesta
        if (!$domain) {
            // Estrae il dominio principale dalla richiesta
            // Ad esempio, da "admin.example.com" ottiene ".example.com"
            $hostParts = explode('.', $request->getHost());
            
            // Se il dominio ha almeno 2 parti (es. example.com)
            if (count($hostParts) >= 2) {
                // Rimuovi i sottodomini e aggiungi il punto iniziale
                $domain = '.' . implode('.', array_slice($hostParts, -2));
            }
        }

        $cookie = Cookie::make('auth_token', $token, 120, path:'/', domain: $domain );

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

        // Imposta il dominio base con un punto all'inizio
        $domain = config('session.domain'); // Ottieni dal file config/session.php
        
        // Se il dominio non è configurato, prova a dedurlo dalla richiesta
        if (!$domain) {
            // Estrae il dominio principale dalla richiesta
            // Ad esempio, da "admin.example.com" ottiene ".example.com"
            $hostParts = explode('.', $request->getHost());
            
            // Se il dominio ha almeno 2 parti (es. example.com)
            if (count($hostParts) >= 2) {
                // Rimuovi i sottodomini e aggiungi il punto iniziale
                $domain = '.' . implode('.', array_slice($hostParts, -2));
            }
        }
       
        return back()->withoutCookie('auth_token', '/', $domain);
    }
     
}
