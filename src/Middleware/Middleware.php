<?php

namespace Sanjayojha\PhpRestApi\Middleware;

use Sanjayojha\PhpRestApi\Core\App;

class Middleware
{
    protected const array MAP = [
        Auth::class
    ];

    public static function resolve(?string $middleware): void
    {
        if (!in_array($middleware, self::MAP)) {
            throw new \Exception("Middleware $middleware not found");
        }

        App::resolve($middleware)->handle();
    }
}
