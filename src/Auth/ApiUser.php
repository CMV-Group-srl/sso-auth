<?php

namespace Cmvgroup\SSOAuth\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model implements Authenticatable
{
    protected $attributes;

    protected $primaryKey = 'ID_UTENTE';

    protected $table = null;

    public function __construct(?array $attributes)
    {
        $this->attributes = $attributes;

        foreach($attributes as $key => $value)
            $this->$key = $value;
    }

    public function getAuthIdentifierName()
    {
        return 'ID_UTENTE';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes[$this->getAuthIdentifierName()] ?? null;
    }

    public function getAuthPasswordName() {
        return 'PASSWORD2';
    }

    public function getAuthPassword()
    {
        return $this->attributes[$this->getAuthPasswordName()] ?? null;
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
