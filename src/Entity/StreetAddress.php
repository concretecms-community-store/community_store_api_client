<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class StreetAddress extends Entity
{
    public string $address1;

    public string $address2;

    public string $address3;

    public string $city;

    public string $stateProvince;

    public string $country;

    public string $postalCode;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->address1 = $data['address1'] ?? '';
        $this->address2 = $data['address2'] ?? '';
        $this->address3 = $data['address3'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->stateProvince = $data['state_province'] ?? '';
        $this->country = $data['country'] ?? '';
        $this->postalCode = $data['postal_code'] ?? '';
    }
}
