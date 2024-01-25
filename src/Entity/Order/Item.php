<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity\Order;

use CommunityStore\APIClient\Entity;
use CommunityStore\APIClient\Entity\Order\Item\Option;
use RuntimeException;

/**
 * @readonly
 */
class Item extends Entity
{
    public int $id;

    public string $name;

    public string $sku;

    public float $quantity;

    public float $price;

    /**
     * @var \CommunityStore\APIClient\Entity\Order\Item\Option[]
     */
    public array $options = [];

    /**
     * @var string[]
     */
    public array $uploads;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->id = (int) $data['id'];
        $this->name = $data['name'];
        $this->sku = (string) $data['sku'];
        $this->quantity = $data['quantity'];
        $this->price = $data['price'];
        foreach ($data['options'] as $optionData) {
            $this->options[] = new Option($optionData);
        }
        $this->uploads = $data['uploads'] ?? [];
    }

    public function getOptionByHandle(string $handle): ?Option
    {
        foreach ($this->options as $option) {
            if ($option->handle === $handle) {
                return $option;
            }
        }

        return null;
    }

    /**
     * @throws \RuntimeException if more that one option has the requested name
     */
    public function getOptionByName(string $name): ?Option
    {
        $result = null;
        foreach ($this->options as $option) {
            if ($option->name === $name) {
                if ($result !== null) {
                    throw new RuntimeException("More that one option found with name '{$name}'");
                }
                $result = $option;
            }
        }

        return $result;
    }
}
