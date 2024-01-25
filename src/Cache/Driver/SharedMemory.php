<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Cache\Driver;

use CommunityStore\APIClient\Cache\Driver;

use DateTimeInterface;

/**
 * @internal
 */
final class SharedMemory implements Driver
{
    private static ?self $instance = null;

    private array $store = [];

    private function __construct() {}

    public static function getInstance(): self
    {
        return self::$instance ?: (self::$instance = new self());
    }

    /**
     * {@inheritdoc}
     *
     * @see \CommunityStore\APIClient\Cache\Driver::getString()
     */
    public function getString(string $key): ?string
    {
        if (isset($this->store[$key])) {
            if (is_string($this->store[$key][0]) && $this->store[$key][1] <= time()) {
                return $this->store[$key][0];
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
        $this->store[$key] = [$value, $expiresAt->getTimestamp()];
    }

    public static function clear(): void
    {
        self::$store = [];
    }
}
