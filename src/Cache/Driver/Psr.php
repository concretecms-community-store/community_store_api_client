<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Cache\Driver;

use CommunityStore\APIClient\Cache\Driver;
use DateTimeInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @internal
 */
final class Psr implements Driver
{
    private CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     *
     * @see \CommunityStore\APIClient\Cache\Driver::getString()
     */
    public function getString(string $key): ?string
    {
        $cacheItem = $this->cache->getItem($key);
        if ($cacheItem->isHit()) {
            $value = $cacheItem->get();
            if (is_string($value)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \CommunityStore\APIClient\Cache\Driver::setString()
     */
    public function setString(string $key, string $value, DateTimeInterface $expiresAt): void
    {
        $cacheItem = $this->cache->getItem($key);
        $cacheItem
            ->expiresAt($expiresAt)
            ->set($value)
        ;
        $this->cache->save($cacheItem);
    }
}
