<?php

namespace Tunaaoguzhann\PhpAuthApi\Services;

use Predis\Client;
use Tunaaoguzhann\PhpAuthApi\Config\Redis;

class RedisService
{
    private Client $redis;

    public function __construct()
    {
        $this->redis = new Client(Redis::getConfig());
    }

    public function setToken(int $userId, string $token, int $expiry): void
    {
        $key = Redis::getTokenKey($userId);
        $this->redis->setex($key, $expiry, $token);
    }

    public function getToken(int $userId): ?string
    {
        $key = Redis::getTokenKey($userId);
        return $this->redis->get($key);
    }

    public function deleteToken(int $userId): void
    {
        $key = Redis::getTokenKey($userId);
        $this->redis->del($key);
    }
} 