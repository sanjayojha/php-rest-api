<?php

namespace Sanjayojha\PhpRestApi\Core;

class Request
{
    private string $path;
    private string $method;
    private array $queryParams;
    private array $headers;
    private array $body;
    private array $files;
    private array $cookies;

    public function __construct()
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $this->path = $uri['path'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->queryParams = [];
        parse_str($uri['query'] ?? '', $this->queryParams);
        $this->headers = getallheaders();
        $this->body = json_decode(file_get_contents('php://input'), true) ?? $_POST ?? [];
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): array
    {
        return $this->queryParams;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getIpAddress(): string
    {
        return
            $_SERVER['HTTP_CLIENT_IP'] ??
            explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '')[0] ?: null ??
            $_SERVER['REMOTE_ADDR'] ??
            '0.0.0.0';
    }

    public function token(): string
    {
        return $this->queryParams['appid'] ?? '';
    }
}
