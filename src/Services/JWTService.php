<?php

namespace Tunaaoguzhann\PhpAuthApi\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private string $key;
    private int $expiryTime;

    public function __construct()
    {
        $this->key = $_ENV['JWT_SECRET'];
        $this->expiryTime = (int)$_ENV['JWT_EXPIRES_IN'];
    }

    public function generateToken(int $userId): string
    {
        $payload = [
            'user_id' => $userId,
            'iat' => time(),
            'exp' => time() + $this->expiryTime
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validateToken(string $token): ?int
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return $decoded->user_id;
        } catch (\Exception $e) {
            return null;
        }
    }
} 