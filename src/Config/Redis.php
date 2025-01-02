<?php

namespace Tunaaoguzhann\PhpAuthApi\Config;

class Redis
{
    public static function getConfig(): array
    {
        return [
            'scheme' => 'tcp',
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT']
        ];
    }

    public static function getTokenKey(int $userId): string
    {
        return "auth:token:{$userId}";
    }

    public static function getTokenExpiry(): int
    {
        return (int)$_ENV['JWT_EXPIRES_IN'];
    }
} 