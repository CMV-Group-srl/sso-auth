<?php

namespace Cmvgroup\SSOAuth\Auth\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;
use Cmvgroup\SSOAuth\Auth\ApiUser;

class ApiUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        $response = Http::withToken($token)->get(config('sso-auth.sso_url') . config('sso-auth.user_url'));

        if ($response->successful()) {
            return new ApiUser($response->json());
        }

        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // opzionale: salva il remember token tramite API
        // Http::post(...);
    }

    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // già gestito in retrieveByCredentials (se vuoi separare login/validazione, modificalo)
        return true;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false)
    {
        return;
    }
}
