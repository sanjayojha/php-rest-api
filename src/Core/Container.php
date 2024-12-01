<?php

namespace Sanjayojha\PhpRestApi\Core;


use Sanjayojha\PhpRestApi\Interfaces\ContainerInterface;
use Sanjayojha\PhpRestApi\Exception\ContainerException\NotFoundException;

class Container implements ContainerInterface
{
    protected array $bindings = [];

    public function set(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    public function get(string $key): object
    {
        if (!array_key_exists($key, $this->bindings)) {
            throw new NotFoundException("No matching binding found for '$key'");
        }

        $resolver = $this->bindings[$key];
        return call_user_func($resolver);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->bindings);
    }
}
