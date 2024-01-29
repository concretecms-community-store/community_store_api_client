<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Configuration;

use CommunityStore\APIClient\Entity;
use DateTimeZone;

/**
 * @readonly
 */
class System extends Entity
{
    /**
     * @since community_store_api v1.0.5-alpha1
     */
    public ?DateTimeZone $timeZone;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->timeZone = empty($data['time_zone']) ? null : new DateTimeZone($data['time_zone']);
    }
}
