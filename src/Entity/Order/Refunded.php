<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order;

use CommunityStore\APIClient\Entity;
use DateTimeImmutable;

/**
 * @readonly
 */
class Refunded extends Entity
{
    public DateTimeImmutable $date;

    public string $reason;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->date = static::parseDateTime($data['date']);
        $this->reason = $data['reason'];
    }
}
