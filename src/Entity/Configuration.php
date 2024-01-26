<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity;

use CommunityStore\APIClient\Entity;
use CommunityStore\APIClient\Entity\Configuration\Currency;
use CommunityStore\APIClient\Entity\Configuration\PackageInfo;
use CommunityStore\APIClient\Entity\Configuration\System;

/**
 * @readonly
 */
class Configuration extends Entity
{
    public array $data;

    public Currency $currency;

    public PackageInfo $communityStore;

    public PackageInfo $communityStoreAPI;

    public System $system;
    
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->currency = new Currency($data['currency']);
        $this->communityStore = new PackageInfo($data['community_store'] ?? []);
        $this->communityStoreAPI = new PackageInfo($data['community_store_api'] ?? []);
        $this->system = new System($data['system'] ?? []);
    }
}
