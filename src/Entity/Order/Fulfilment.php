<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class Fulfilment extends Entity
{
    public string $statusName;

    /**
     * @see \CommunityStore\APIClient\Entity\FulfilmentStatus default handles are the constants in that class
     */
    public string $statusHandle;

    public string $trackingID;

    public string $trackingCode;

    public string $trackingURL;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->statusName = $data['status'];
        $this->statusHandle = $data['handle'];
        $this->trackingID = (string) $data['tracking_id'];
        $this->trackingCode = (string) $data['tracking_code'];
        $this->trackingURL = (string) $data['tracking_url'];
    }
}
