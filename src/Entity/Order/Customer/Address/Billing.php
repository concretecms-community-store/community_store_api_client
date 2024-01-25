<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order\Customer\Address;

use CommunityStore\APIClient\Entity\Order\Customer\Address;

/**
 * @readonly
 */
class Billing extends Address
{
    public string $phone;

    public string $vatNumber;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->phone = (string) $data['phone'];
        $this->vatNumber = isset($data['vat_number']) ? $data['vat_number'] : '';
    }
}
