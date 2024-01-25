<?php

declare(strict_types=1);

namespace CommunityStore\APIClient;

use DateTimeImmutable;
use DateTimeZone;

/**
 * @readonly
 */
abstract class Entity
{
    public array $data;

    protected function __construct(array $data)
    {
        $this->data = $data;
    }

    protected static function parseDateTime(array $data): DateTimeImmutable
    {
        $originalTimeZone = new DateTimeZone($data['timezone']);
        $originalDateTime = new DateTimeImmutable($data['date'], $originalTimeZone);

        return (new DateTimeImmutable())->setTimestamp($originalDateTime->getTimestamp());
    }
}
