<?php

namespace Sanjayojha\PhpRestApi\Core;

class App
{
    protected static Container $container;

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    public static function getContainer(): Container
    {
        return self::$container;
    }

    public static function resolve(string $class): object
    {
        return self::$container->get($class);
    }
}
