<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Query;

use DateTimeInterface;

class Orders
{
    protected const DATE_FORMAT = 'Y-m-d';

    public ?int $pageSize = null;

    /**
     * @see \CommunityStore\APIClient\Entity\FulfilmentStatus default handles are the constants in that class
     */
    public string $status = '';

    /**
     * @see \CommunityStore\APIClient\Query\Orders\PaymentStatus
     */
    public string $paymentStatus = '';

    public ?DateTimeInterface $fromDate = null;

    public ?DateTimeInterface $toDate = null;

    public ?bool $paid = null;

    public ?bool $cancelled = null;

    public ?bool $refunded = null;

    public ?bool $shippable = null;

    /**
     * @since Community Store v2.6.2
     * @since Community Store API v1.0.4
     */
    public array $orderIDs = [];

    public array $customFields = [];

    public function toQueryString(): string
    {
        $queryString = http_build_query($this->customFields + $this->getStandardFields());

        return ($queryString === '' ? '' : '?') . $queryString;
    }

    public function __toString(): string
    {
        return $this->toQueryString();
    }

    protected function getStandardFields(): array
    {
        $result = [];
        if ($this->pageSize !== null && $this->pageSize > 0) {
            $result['paging'] = $this->pageSize;
        }
        if ($this->status !== '') {
            $result['status'] = $this->status;
        }
        if ($this->paymentStatus !== '') {
            $result['paymentStatus'] = $this->paymentStatus;
        }
        if ($this->fromDate !== null) {
            $result['fromDate'] = $this->fromDate->format(static::DATE_FORMAT);
        }
        if ($this->toDate !== null) {
            $result['toDate'] = $this->toDate->format(static::DATE_FORMAT);
        }
        if ($this->paid !== null) {
            $result['paid'] = $this->paid ? '1' : '0';
        }
        if ($this->cancelled !== null) {
            $result['cancelled'] = $this->cancelled ? '1' : '0';
        }
        if ($this->refunded !== null) {
            $result['refunded'] = $this->refunded ? '1' : '0';
        }
        if ($this->shippable !== null) {
            $result['shippable'] = $this->shippable ? '1' : '0';
        }
        $ids = array_values($this->orderIDs);
        switch (count($this->orderIDs)) {
            case 0:
                break;
            case 1:
                $result['id'] = $ids[0];
                break;
            default:
                $result['id'] = $ids;
                break;
        }

        return $result;
    }
}
