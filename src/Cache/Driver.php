<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Cache;

use DateTimeInterface;

/**
 * @internal
 */
interface Driver
{
    public function getString(string $key): ?string;

    public function setString(string $key, string $value, DateTimeInterface $expiresAt): void;
}
