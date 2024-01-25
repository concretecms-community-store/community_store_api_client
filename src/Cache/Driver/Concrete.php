<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Cache\Driver;

use CommunityStore\APIClient\Cache\Driver;
use Concrete\Core\Cache\Cache;
use DateTimeInterface;

/**
 * @internal
 */
final class Concrete implements Driver
{
    private Cache $cache;

    public function __construct(Cache $cache)
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
        if ($this->cache->isEnabled()) {
            $result = null;
            $cacheItem = $this->cache->getItem($key);
            if ($cacheItem->isHit()) {
                $value = $cacheItem->get();
                if (is_string($value)) {
                    $result = $value;
                }
            }
        } else {
            $result = SharedMemory::getInstance()->getString($key);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see \CommunityStore\APIClient\Cache\Driver::setString()
     */
    public function setString(string $key, string $value, DateTimeInterface $expiresAt): void
    {
        if ($this->cache->isEnabled()) {
            $cacheItem = $this->cache->getItem($key);
            $cacheItem
                ->expiresAt($expiresAt)
                ->set($value)
            ;
            $this->cache->save($cacheItem);
        } else {
            SharedMemory::getInstance()->setString($key, $value, $expiresAt);
        }
    }
}
