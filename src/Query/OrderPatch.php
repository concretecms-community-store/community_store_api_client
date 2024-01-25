<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Query;

use CommunityStore\APIClient\Query\OrderPatch\Fulfilment;

class OrderPatch
{
    /**
     * @readonly
     */
    public int $id;

    public Fulfilment $fulfilment;

    public array $additionalData = [];

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->fulfilment = new Fulfilment();
    }

    /**
     * {@inheritdoc}
     *
     * @see \JsonSerializable::jsonSerialize()
     */
    public function getFields(): array
    {
        $result = $this->additionalData;
        $fulfilmentFields = $this->fulfilment->getFields();
        if ($fulfilmentFields !== []) {
            if (isset($result['fulfilment'])) {
                $result['fulfilment'] += $fulfilmentFields;
            } else {
                $result['fulfilment'] = $fulfilmentFields;
            }
        }

        return $result;
    }
}
