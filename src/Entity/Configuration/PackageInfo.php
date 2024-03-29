<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Configuration;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class PackageInfo extends Entity
{
    /**
     * @since community_store_api v1.0.5-alpha1
     */
    public string $version;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->version = empty($data['version']) ? '' : $data['version'];
    }
}
