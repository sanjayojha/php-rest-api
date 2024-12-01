<?php

namespace Sanjayojha\PhpRestApi\Interfaces;

use Sanjayojha\PhpRestApi\Core\Database;

interface GatewayInterface
{
    public function __construct(Database $database);
    public function find(int $id): ?array;
    public function findAll(array $conditions = []): array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
