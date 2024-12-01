<?php

namespace Sanjayojha\PhpRestApi\Interfaces;

interface ContainerInterface
{
    public function set(string $key, callable $resolver): void;
    public function get(string $key): object;
    public function has(string $key): bool;
}
