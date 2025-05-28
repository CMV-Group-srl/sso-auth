<?php

namespace Cmvgroup\SSOAuth;

use Cmvgroup\SSOAuth\Auth\Guards\TokenCookieGuard;
use Cmvgroup\SSOAuth\Http\Middleware\AuthenticateWithApi;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;
use Cmvgroup\SSOAuth\Auth\Providers\ApiUserProvider;

class SSOAuthServiceProvider extends AuthServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->registerPolicies();

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/sso-auth.php' => config_path('sso-auth.php'),
        ]);

        $router->aliasMiddleware('auth.api', AuthenticateWithApi::class);

        Auth::extend('cookie_token', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider'] ?? 'api_users');
            $request = $app->make('request');
    
            return new TokenCookieGuard($provider, $request, $config['cookie'] ?? 'auth_token');
        });

        Auth::provider('api-user', function ($app, array $config) {
            return new ApiUserProvider();
        });

        // Fai il merge della configurazione per aggiungere la nuova guard dinamicamente
        config([
            'auth.providers.api_users' => [
                'driver' => 'api-user',
            ],
            'auth.guards.api' => [
                'driver' => 'cookie_token',
                'provider' => 'api-users',
                'cookie' => 'auth_token', // opzionale
            ],
        ]);
        
    }
}
