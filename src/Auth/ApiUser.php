<?php

namespace Cmvgroup\SSOAuth\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class ApiUser implements Authenticatable
{
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAuthIdentifierName()
    {
        return 'ID_UTENTE';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['ID_UTENTE'] ?? null;
    }

    public function getAuthPassword()
    {
        return $this->attributes['PASSWORD2'] ?? null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        return 'TOKEN';
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
