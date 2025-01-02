<?php

namespace Tunaaoguzhann\PhpAuthApi\Http;

class Response
{
    private mixed $data;
    private int $statusCode;
    private array $headers;

    public function __construct(mixed $data = null, int $statusCode = 200, array $headers = [])
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = array_merge([
            'Content-Type' => 'application/json'
        ], $headers);
    }

    public static function success(mixed $data = null, int $statusCode = 200): self
    {
        return new self([
            'status' => 'success',
            'data' => $data
        ], $statusCode);
    }

    public static function error(string $message, int $statusCode = 400): self
    {
        return new self([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }

    public static function unauthorized(string $message = 'Unauthorized'): self
    {
        return self::error($message, 401);
    }

    public static function notFound(string $message = 'Not found'): self
    {
        return self::error($message, 404);
    }

    public static function methodNotAllowed(string $message = 'Method not allowed'): self
    {
        return self::error($message, 405);
    }

    public static function validationError(string $message): self
    {
        return self::error($message, 422);
    }

    public static function serverError(string $message = 'Internal server error'): self
    {
        return self::error($message, 500);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo json_encode($this->data);
    }

    public function withHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }
} 