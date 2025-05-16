<?php

namespace Cmvgroup\SSOAuth\Auth\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TokenCookieGuard implements Guard
{
    protected $request;
    protected $provider;
    protected $user;
    protected $cookieName;

    public function __construct(UserProvider $provider, Request $request, $cookieName = 'auth_token')
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->cookieName = $cookieName;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->cookie($this->cookieName);

        if (!$token) {
            return null;
        }

        // Puoi fare cache qui se vuoi
        $user = $this->retrieveUserFromApi($token);


        if ($user) {
            $this->user = $user;
            return $this->user;
        }

        return null;
    }

    public function check()
    {
        return !is_null($this->user());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        //return $this->user() ? $this->user()->getAuthIdentifier() : null;
        return $this->user() ? $this->user()->ID_UTENTE : null;
    }

    public function validate(array $credentials = [])
    {
        // Non usato qui
        return false;
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }

    public function hasUser() {
        return !is_null($this->user());
    }

    protected function retrieveUserFromApi($token)
    {
        // Chiave cache basata sul token (puoi anche usare un hash del token)
        $cacheKey = 'auth_user_' . md5($token);

        // Prova a recuperare dalla cache
        $minutes = config('sso-auth.cache_duration', 5);

        if ( $minutes ) {
            $user = Cache::remember($cacheKey, now()->addMinutes($minutes), function () use ($token) {
                return $this->getUserFromApi($token);
            });
        } else {
            $user = $this->getUserFromApi($token);
        }

        return $user;
    }
    
    private function getUserFromApi($token) {
        try {
            $response = Http::withToken($token)->get(config('sso-auth.sso_url') . config('sso-auth.user_url'));
            
            if ($response->successful()) {
                $userData = $response->json();

                // Restituisci oggetto utente da provider (opzionale)
                //return $this->provider->retrieveById($userData['ID_UTENTE']);
                return new \ArrayObject($userData, \ArrayObject::ARRAY_AS_PROPS);
            }
        } catch (\Exception $e) {;
            
            // Logga in caso di problemi con l'API
            Log::warning("Errore chiamata auth API: " . $e->getMessage());
        }

        return null;
    }
}
