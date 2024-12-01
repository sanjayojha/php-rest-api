<?php

namespace Sanjayojha\PhpRestApi\Core;

use PDO;

class Database
{
    protected PDO $connection;

    public function __construct(array $config)
    {
        $this->connection = new PDO(
            $this->dsn($config),
            $config["username"] ?? '',
            $config["password"] ?? '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    protected function dsn(array $config): string
    {
        $driver = $config["driver"];

        $dsn = match ($driver) {
            "mysql", "pgsql" => "$driver:host={$config['host']};port={$config['port']};dbname={$config['name']}",
            "sqlite" => "sqlite:" . BASE_PATH . $config['name'],
            "sqlsrv" => "sqlsrv:Server={$config['host']},{$config['port']};Database={$config['name']}",
        };
        return $dsn;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
