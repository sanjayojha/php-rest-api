<?php

namespace Sanjayojha\PhpRestApi\Middleware;

use Sanjayojha\PhpRestApi\Core\Request;
use Sanjayojha\PhpRestApi\Core\Database;
use Sanjayojha\PhpRestApi\Exception\HTTPException\HTTPUnauthorizedException;

use PDO;

class Auth
{
    protected PDO $connection;

    public function __construct(Database $database, protected Request $request)
    {
        $this->connection = $database->getConnection();
    }

    public function handle(): void
    {
        $stmt = $this->connection->prepare("SELECT * FROM tokens WHERE token = :token");
        $stmt->execute(["token" => $this->request->token()]);

        if (!$stmt->fetch()) {
            throw new HTTPUnauthorizedException("Unauthorized Attempt from {$this->request->getIpAddress()}");
        }
    }
}
