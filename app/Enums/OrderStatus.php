<?php

namespace App\Enums;

class OrderStatus
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    public static function all()
    {
        return [
            self::PENDING,
            self::PROCESSING,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }
}
