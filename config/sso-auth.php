<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Cache duration
    |--------------------------------------------------------------------------
    |
    | The duration in minutes that the cache will be valid.
    | If you don't want to use the cache, set this to 0.
    |
    */

    'cache_duration' => 5,

    /*
    |--------------------------------------------------------------------------
    | Authentication app URL (SSO)
    |--------------------------------------------------------------------------
    |
    | The URL of the authentication app.
    |
    */

    'sso_url' => env('SSO_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Login page
    |--------------------------------------------------------------------------
    |
    | The login page of the authentication app.
    |
    */

    'login_page' => env('SSO_LOGIN_PAGE', '/'),

    /*
    |--------------------------------------------------------------------------
    | User API resource route
    |--------------------------------------------------------------------------
    |
    | The route of the user API resource.
    |
    */

    'user_url' => env('SSO_USER_URL', '/api/user'),

];
