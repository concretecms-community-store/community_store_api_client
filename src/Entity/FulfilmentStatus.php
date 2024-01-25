<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class FulfilmentStatus extends Entity
{
    public const AWAITING_PROCESSING = 'incomplete';

    public const PROCESSING = 'processing';

    public const SHIPPED = 'shipped';

    public const DELIVERED = 'delivered';

    public const WILL_NOT_DELIVER = 'nodelivery';

    public const RETURNED = 'returned';

    public int $id;

    public string $handle;

    public string $name;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->id = (int) $data['id'];
        $this->handle = $data['handle'];
        $this->name = $data['name'];
    }
}
