<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class Attribute extends Entity
{
    public string $handle;

    public string $name;

    /**
     * @var mixed
     */
    public $value;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->handle = $data['handle'];
        $this->name = $data['name'];
        $this->value = $data['value'];
    }
}
