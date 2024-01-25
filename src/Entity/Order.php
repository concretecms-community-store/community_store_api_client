<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity;

use CommunityStore\APIClient\Entity;
use CommunityStore\APIClient\Entity\Order\Attribute;
use CommunityStore\APIClient\Entity\Order\Customer;
use CommunityStore\APIClient\Entity\Order\Fulfilment;
use CommunityStore\APIClient\Entity\Order\Item;
use CommunityStore\APIClient\Entity\Order\Refunded;
use DateTimeImmutable;
use RuntimeException;

/**
 * @readonly
 */
class Order extends Entity
{
    public int $id;

    public DateTimeImmutable $datePlaced;

    public float $total;

    public string $paymentMethodName;

    public ?DateTimeImmutable $paymentDate;

    public string $paymentReference;

    public string $shippingMethodName;

    public Fulfilment $fulfilment;

    public string $locale;

    public Customer $customer;

    /**
     * @var \CommunityStore\APIClient\Entity\Order\Item[]
     */
    public array $items = [];

    /**
     * @var \CommunityStore\APIClient\Entity\Order\Attribute[]
     */
    public array $attributes = [];

    public ?Refunded $refunded;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->id = $data['id'];
        $this->datePlaced = static::parseDateTime($data['date_placed']);
        $this->total = $data['total'];
        $this->paymentMethodName = (string) $data['payment_method'];
        $this->paymentDate = empty($data['payment_date']) ? null : static::parseDateTime($data['payment_date']);
        $this->paymentReference = (string) $data['payment_reference'];
        $this->shippingMethodName = (string) $data['shipping_method'];
        $this->fulfilment = new Fulfilment($data['fulfilment']);
        $this->locale = (string) $data['locale'];
        $this->customer = new Customer($data['customer']);
        foreach ($data['items'] as $itemData) {
            $this->items[] = new Item($itemData);
        }
        foreach ($data['attributes'] as $attributeData) {
            $this->attributes[] = new Attribute($attributeData);
        }
        $this->refunded = isset($data['refunded']) ? new Refunded($data['refunded']) : null;

        return $data;
    }

    public function getAttributeByHandle(string $handle): ?Attribute
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->handle === $handle) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * @throws \RuntimeException if more that one attribute has the requested name
     */
    public function getAttributeByName(string $name): ?Attribute
    {
        $result = null;
        foreach ($this->attributes as $attribute) {
            if ($attribute->name === $name) {
                if ($result !== null) {
                    throw new RuntimeException("More that one attribute found with name '{$name}'");
                }
                $result = $attribute;
            }
        }

        return $result;
    }
}
