<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Configuration;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class PackageInfo extends Entity
{
    public string $version;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->version = $data['version'];
    }
}
