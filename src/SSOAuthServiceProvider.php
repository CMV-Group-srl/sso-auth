<?php

namespace Cmvgroup\SSOAuth;

use Cmvgroup\SSOAuth\Auth\Guards\TokenCookieGuard;
use Cmvgroup\SSOAuth\Http\Middleware\AuthenticateWithApi;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;

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
            $provider = Auth::createUserProvider($config['provider']);
            $request = $app->make('request');
    
            return new TokenCookieGuard($provider, $request, $config['cookie'] ?? 'auth_token');
        });

        // Fai il merge della configurazione per aggiungere la nuova guard dinamicamente
        config([
            'auth.guards.api' => [
                'driver' => 'cookie_token',
                'provider' => 'users',
                'cookie' => 'auth_token', // opzionale
            ],
        ]);
        
    }
}
