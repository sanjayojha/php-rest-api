<?php

namespace Sanjayojha\PhpRestApi\Gateways;

use Sanjayojha\PhpRestApi\Core\Database;
use Sanjayojha\PhpRestApi\Interfaces\GatewayInterface;
use Sanjayojha\PhpRestApi\Exception\GatewayException\UnknownColumnException;
use Sanjayojha\PhpRestApi\Exception\GatewayException\RecordNotFoundException;

use PDO;

class CitiesTableGateway implements GatewayInterface
{
    protected PDO $connection;

    public const array ALLOWED_COLUMNS = [
        'id',
        'name',
        'lat',
        'lon',
        'population',
        'country'
    ];

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function find(int $id): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM cities WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function findAll(array $conditions = []): array
    {
        //$this->validate($conditions);
        $query = "SELECT * FROM cities";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = "$column COLLATE NOCASE = :$column";
                $params[":$column"] = $value;
            }
            $query .= " WHERE " . implode(" AND ", $clauses);
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    public function insert(array $data): bool
    {
        //$this->validate($data);
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->connection->prepare("INSERT INTO cities ($columns) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        //$this->validate($data);
        //$this->checkId($id);
        $setCluase = implode(", ", array_map(fn($column) => "$column = :$column", array_keys($data)));
        $data['id'] = $id;
        $stmt = $this->connection->prepare("UPDATE cities SET $setCluase WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        //$this->checkId($id);
        $stmt = $this->connection->prepare("DELETE FROM cities WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    protected function validate(array $data): void
    {
        $columns = array_keys($data);
        $invalidColumns = array_diff($columns, self::ALLOWED_COLUMNS);
        if (!empty($invalidColumns)) {
            $invalidColumns = implode(", ", $invalidColumns);
            throw new UnknownColumnException("Invalid column(s): $invalidColumns");
        }
    }

    protected function checkId(int $id): void
    {
        if ($this->find($id) === []) {
            throw new RecordNotFoundException("Record with id $id not found");
        }
    }
}
