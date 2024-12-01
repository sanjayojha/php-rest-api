<?php

namespace Sanjayojha\PhpRestApi\Core;

use Sanjayojha\PhpRestApi\Enum\ResponseStatus;

class Responder
{

    public function __construct()
    {
        header("Content-Type: application/json");
        header("X-Content-Type-Options: nosniff");

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");

        header("Expires: 0");
    }

    public function respond(ResponseStatus $status, string $message, array $data = []): never
    {
        http_response_code($status->value);

        $response = match (true) {
            empty($data) => [
                'status' => $status,
                'message' => $message,
            ],
            default => [
                'data' => $data,
            ],
        };
        $response = $this->utf8ize($response);
        $json = json_encode($response, JSON_PRETTY_PRINT);
        if ($json === false) {
            echo 'JSON encoding error: ' . json_last_error_msg();
        } else {
            echo $json;
        }
        die();
    }

    public function ok(string $message = "Resource Retrieved", array $data = []): never
    {
        $this->respond(ResponseStatus::OK, $message, $data);
    }

    public function created(string $message = "Resource Created", array $data = []): never
    {
        $this->respond(ResponseStatus::CREATED, $message, $data);
    }

    public function noContent(string $message = "No Content"): never
    {
        $this->respond(ResponseStatus::NO_CONTENT, $message);
    }

    public function badRequest(string $message = "Bad Request", array $data = []): never
    {
        $this->respond(ResponseStatus::BAD_REQUEST, $message, $data);
    }

    public function unauthorized(string $message = "Unauthorized Access", array $data = []): never
    {
        $this->respond(ResponseStatus::UNAUTHORIZED, $message, $data);
    }

    public function forbidden(string $message = "Forbidden", array $data = []): never
    {
        $this->respond(ResponseStatus::FORBIDDEN, $message, $data);
    }

    public function notFound(string $message = "Not Found", array $data = []): never
    {
        $this->respond(ResponseStatus::NOT_FOUND, $message, $data);
    }

    public function methodNotAllowed(string $message = "Method Not Allowed", array $data = []): never
    {
        $this->respond(ResponseStatus::METHOD_NOT_ALLOWED, $message, $data);
    }

    public function conflict(string $message = "Conflict", array $data = []): never
    {
        $this->respond(ResponseStatus::CONFLICT, $message, $data);
    }

    public function unprocessableEntity(string $message = "Unprocessable Entity", array $data = []): never
    {
        $this->respond(ResponseStatus::UNPROCESSABLE_ENTITY, $message, $data);
    }

    public function internalServerError(string $message = "Internal Server Error", array $data = []): never
    {
        $this->respond(ResponseStatus::INTERNAL_SERVER_ERROR, $message, $data);
    }

    public function notImplemented(string $message = "Not Implemented", array $data = []): never
    {
        $this->respond(ResponseStatus::NOT_IMPLEMENTED, $message, $data);
    }

    public function serviceUnavailable(string $message = "Service Unavailable", array $data = []): never
    {
        $this->respond(ResponseStatus::SERVICE_UNAVAILABLE, $message, $data);
    }

    public function gatewayTimeout(string $message = "Gateway Timeout", array $data = []): never
    {
        $this->respond(ResponseStatus::GATEWAY_TIMEOUT, $message, $data);
    }

    private function utf8ize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }
        return $data;
    }
}
