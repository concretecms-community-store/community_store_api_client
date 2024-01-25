<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity;

use CommunityStore\APIClient\Entity;
use CommunityStore\APIClient\Entity\Configuration\Currency;
use CommunityStore\APIClient\Entity\Configuration\PackageInfo;

/**
 * @readonly
 */
class Configuration extends Entity
{
    public array $data;

    public Currency $currency;

    public ?PackageInfo $communityStore;

    /**
     * @since community_store_api v1.0.5-alpha1
     */
    public ?PackageInfo $communityStoreAPI;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->currency = new Currency($data['currency']);
        $this->communityStore = isset($data['community_store']) ? new PackageInfo($data['community_store']) : null;
        $this->communityStoreAPI = isset($data['community_store_api']) ? new PackageInfo($data['community_store_api']) : null;
    }
}
