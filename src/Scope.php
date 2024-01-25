<?php

declare(strict_types=1);

namespace CommunityStore\APIClient;

use InvalidArgumentException;
use ReflectionClass;

class Scope
{
    public const CONFIG_READ = 'cs:config:read';

    public const PRODUCTS_READ = 'cs:products:read';

    public const PRODUCTS_WRITE = 'cs:products:write';

    public const ORDERS_READ = 'cs:orders:read';

    public const ORDERS_WRITE = 'cs:orders:write';

    /**
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    public static function validate(array $scopes): array
    {
        if ($scopes === []) {
            throw new InvalidArgumentException('No scopes specified');
        }
        $unknownScopes = array_diff($scopes, static::getAll());
        if ($unknownScopes !== []) {
            throw new InvalidArgumentException("Unrecognized scopes detected:\n- " . implode("\n- ", $unknownScopes));
        }
        $scopes = array_unique($scopes, SORT_STRING);
        sort($scopes, SORT_STRING);

        return $scopes;
    }

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        $classInfo = new ReflectionClass(get_called_class());
        $result = [];
        foreach ($classInfo->getConstants() as $value) {
            if (is_string($value) && strpos($value, 'cs:') === 0) {
                $result[] = $value;
            }
        }

        return $result;
    }
}
