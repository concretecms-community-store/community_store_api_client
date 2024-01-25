<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order\Customer;

use CommunityStore\APIClient\Entity;
use CommunityStore\APIClient\Entity\StreetAddress;

/**
 * @readonly
 */
abstract class Address extends Entity
{
    public string $firstName;

    public string $lastName;

    public string $company;

    public StreetAddress $address;

    protected function __construct(array $data)
    {
        parent::__construct($data);
        $this->firstName = (string) $data['first_name'];
        $this->lastName = (string) $data['last_name'];
        $this->company = (string) $data['company'];
        $this->address = new StreetAddress($data['address'] ?? []);
    }
}
