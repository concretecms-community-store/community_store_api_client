<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Query\OrderPatch;

class Fulfilment
{
    public ?string $trackingID = null;

    public ?string $trackingCode = null;

    public ?string $trackingURL = null;

    public string $status = '';

    public function getFields(): array
    {
        $result = [];
        if ($this->trackingID !== null) {
            $result['tracking_id'] = $this->trackingID;
        }
        if ($this->trackingCode !== null) {
            $result['tracking_code'] = $this->trackingCode;
        }
        if ($this->trackingURL !== null) {
            $result['tracking_url'] = $this->trackingURL;
        }
        if ($this->status !== '') {
            $result['handle'] = $this->status;
        }

        return $result;
    }
}
