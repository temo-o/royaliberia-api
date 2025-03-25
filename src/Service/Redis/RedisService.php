<?php

namespace App\Service\Redis;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class RedisService
{
    private RedisAdapter $redis;
    public const TIME_HOUR_IN_SECONDS = 3600;

    public function __construct()
    {
        $redisConnection = RedisAdapter::createConnection(env('REDIS_URL'));
        $this->redis = new RedisAdapter($redisConnection);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setValue(string $key, string $value): void
    {
        $item = $this->redis->getItem($key);
        $item->set($value);

        $item->expiresAfter(self::TIME_HOUR_IN_SECONDS);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getValue($key): mixed
    {
        $item = $this->redis->getItem($key);

        return $item->isHit() ? $item->get() : null;
    }


}