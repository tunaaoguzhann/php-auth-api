<?php

namespace Tunaaoguzhann\PhpAuthApi\Http;

class Request
{
    private array $query;     // GET parametreleri
    private array $request;   // POST/PUT parametreleri
    private array $headers;   // HTTP headers
    private string $method;   // HTTP method
    private string $path;     // Request path

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $this->headers = getallheaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // URL'den path'i al ve index.php'yi kaldır
        $fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->path = str_replace('/index.php', '', $fullPath);
    }

    public static function createFromGlobals(): self
    {
        return new self();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function get(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->request;
    }

    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    public function bearerToken(): ?string
    {
        $header = $this->header('Authorization');
        if (empty($header) || !str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return substr($header, 7);
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($this->method) === strtoupper($method);
    }

    public function isJson(): bool
    {
        return str_contains($this->header('Content-Type', ''), 'application/json');
    }
} 