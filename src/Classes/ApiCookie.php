<?php

namespace Cmvgroup\SSOAuth\Classes;

use Illuminate\Support\Facades\Cookie;

class ApiCookie extends Cookie
{
    public static function make(
        $name,
        $value,
        $minutes = 0,
    ) {
        // Imposta il dominio base con un punto all'inizio
        $domain = self::evaluateDomain();

        return parent::make($name, $value, $minutes, path:'/', domain: $domain );
    }

    public static function expire(string $name, string|null $path = null, string|null $domain = null) 
    {
        $domain = self::evaluateDomain();

        return parent::expire($name, '/', $domain);
    }

    private static function evaluateDomain()  {
        // Imposta il dominio base con un punto all'inizio
        $domain = config('session.domain'); // Ottieni dal file config/session.php
        
        // Se il dominio non è configurato, prova a dedurlo dalla richiesta
        if (!$domain) {
            // Estrae il dominio principale dalla richiesta
            // Ad esempio, da "admin.example.com" ottiene ".example.com"
            $hostParts = explode('.', request()->getHost());
            
            // Se il dominio ha almeno 2 parti (es. example.com)
            if (count($hostParts) >= 2) {
                // Rimuovi i sottodomini e aggiungi il punto iniziale
                $domain = '.' . implode('.', array_slice($hostParts, -2));
            }
        }

        return $domain;
    }
    
}
