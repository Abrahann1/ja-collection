<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $queryParams;
    private array $bodyParams;
    private array $headers;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $parsedUrl = parse_url($requestUri);
        $this->uri = rtrim($parsedUrl['path'] ?? '/', '/') ?: '/';
        $this->queryParams = $_GET;
        $this->headers = function_exists('getallheaders') ? (getallheaders() ?: []) : [];
        $this->bodyParams = $this->parseBody();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $this->queryParams : ($this->queryParams[$key] ?? $default);
    }

    public function body(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $this->bodyParams : ($this->bodyParams[$key] ?? $default);
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->bodyParams);
    }

    public function isJson(): bool
    {
        $contentType = $this->headers['Content-Type'] ?? $this->headers['content-type'] ?? '';
        return str_contains(strtolower((string)$contentType), 'application/json');
    }

    private function parseBody(): array
    {
        if ($this->isJson()) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            return is_array($data) ? $data : [];
        }

        if ($this->method === 'POST') {
            return $_POST;
        }

        if (in_array($this->method, ['PUT', 'PATCH', 'DELETE'], true)) {
            $input = file_get_contents('php://input');
            parse_str($input, $data);
            return is_array($data) ? $data : [];
        }

        return [];
    }
}