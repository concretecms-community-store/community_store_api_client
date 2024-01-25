<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Http;

/**
 * @internal
 */
interface Driver
{
    public function send(string $method, string $url, array $headers = [], ?string $body = null): array;
}
