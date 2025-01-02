<?php

namespace Tunaaoguzhann\PhpAuthApi\Controllers;

use Tunaaoguzhann\PhpAuthApi\Services\RedisService;
use Tunaaoguzhann\PhpAuthApi\Services\JWTService;
use Tunaaoguzhann\PhpAuthApi\Models\User;

class AuthController
{
    private static ?RedisService $redis = null;
    private static ?JWTService $jwt = null;
    private static ?User $user = null;

    private static function init(): void
    {
        if (!self::$redis) {
            self::$redis = new RedisService();
        }
        if (!self::$jwt) {
            self::$jwt = new JWTService();
        }
        if (!self::$user) {
            self::$user = new User();
        }
    }

    public static function validateCredentials(string $email, string $password): ?array
    {
        self::init();
        
        $userData = self::$user->findByEmail($email);
        
        if (!$userData || !password_verify($password, $userData['password'])) {
            return null;
        }

        return $userData;
    }

    public static function generateAuthToken(int $userId): string
    {
        self::init();
        
        $token = self::$jwt->generateToken($userId);
        self::$redis->setToken($userId, $token, (int)$_ENV['JWT_EXPIRES_IN']);
        
        return $token;
    }

    public static function validateToken(?string $token): ?int
    {
        self::init();
        
        if (!$token) {
            return null;
        }
        
        $userId = self::$jwt->validateToken($token);
        
        if (!$userId) {
            return null;
        }
        
        $storedToken = self::$redis->getToken($userId);
        if (!$storedToken || $storedToken !== $token) {
            return null;
        }
        
        return $userId;
    }
} 