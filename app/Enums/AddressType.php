<?php

namespace App\Enums;

class AddressType
{
    public const SHIPPING = 'shipping';
    public const BILLING = 'billing';

    public static function all(): array
    {
        return [
            self::SHIPPING,
            self::BILLING
        ];
    }
} 