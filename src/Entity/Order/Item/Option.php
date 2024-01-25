<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order\Item;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class Option extends Entity
{
    public string $name;

    public string $handle;

    public string $value;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->name = $data['name'];
        $this->handle = $data['handle'];
        $this->value = $data['value'];
    }
}
