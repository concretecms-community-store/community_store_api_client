<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Configuration;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class Currency extends Entity
{
    public string $code;

    public string $symbol;

    public int $decimalDigits;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->code = $data['code'];
        $this->symbol = $data['symbol'];
        $this->decimalDigits = $data['decimal_digits'];
    }
}
