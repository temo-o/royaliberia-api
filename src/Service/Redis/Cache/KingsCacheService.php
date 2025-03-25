<?php

namespace App\Service\Redis\Cache;

use App\Service\Redis\RedisService;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class KingsCacheService
{
    public function __construct(protected CacheItemPoolInterface $cache, int $ttl)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setKing(string $id, string $name): void
    {
        $item = $this->cache->getItem($id);
        $item->set($name);
        $item->expiresAfter(RedisService::TIME_HOUR_IN_SECONDS);
        $this->cache->save($item);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getKing(string $id): mixed
    {
        $item = $this->cache->getItem('*');
        return $item;
        return $item->isHit() ? $item->get() : null;
    }
}