# SSO Auth

This package provides a guard for the a sinsgle-sing-on authentication system.

## Installation

You can install the package via composer.
In your project composer.json file, add the following line:

```json
"require": {
    "cmv-group-srl/sso-auth": "dev-main"
}
```
```json
"repositories": [
    {
    "type": "vcs",
    "url": "https://github.com/Cmv-Group-srl/sso-auth.git"
    }
]
```
Then run the `composer update` command.

Publish the configuration file in order to change the default values:

```bash
php artisan vendor:publish --provider="Cmvgroup\SSOAuth\SSOAuthServiceProvider"
```

## Usage

The package provides a `api` guard (and a `api_users` provider) that automatically retrieves the user from a centralized API.

If you want to add your own guard to the `auth.php` configuration file, you can use the `cookie_token` driver:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'cookie_token',
        'provider' => 'api_users',
    ],
],
```

To protext the routes, you can use the `AuthenticateWithApi` middleware (`api.user`) or with the `auth('api')` helper:

```php
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
```

## Configuration

You can configure the guard by setting the `cookie` option in the `auth.php` configuration file:

```php
'guards' => [
    'api' => [
        'driver' => 'cookie_token',
        'provider' => 'api_users',
        'cookie' => 'auth_token',
    ],
],
```

If you don't set the `cookie` option, the guard will use the default cookie name `auth_token`.

