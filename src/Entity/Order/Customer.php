<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order;

use CommunityStore\APIClient\Entity;
use CommunityStore\APIClient\Entity\Order\Customer\Address\Billing;
use CommunityStore\APIClient\Entity\Order\Customer\Address\Shipping;

/**
 * @readonly
 */
class Customer extends Entity
{
    public string $email;

    public string $username;

    public Billing $billing;

    public Shipping $shipping;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->email = (string) $data['email'];
        $this->username = (string) $data['username'];
        $this->billing = new Billing($data['billing']);
        $this->shipping = new Shipping($data['shipping']);
    }
}
