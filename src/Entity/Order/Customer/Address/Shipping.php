<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order\Customer\Address;

use CommunityStore\APIClient\Entity\Order\Customer\Address;

/**
 * @readonly
 */
class Shipping extends Address
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
