<?php

namespace Domain\Order\Enums;

use Domain\Order\Models\Order;
use Domain\Order\States\CancelledOrderState;
use Domain\Order\States\NewOrderState;
use Domain\Order\States\OrderState;
use Domain\Order\States\PaidOrderState;
use Domain\Order\States\PendingOrderState;

class OrderStatuses
{
    const NEW = 'new';
    const PENDING = 'pending';
    const PAID = 'paid';
    const CANCELLED = 'cancelled';

    public static function getOrderStatuses(): array
    {
        return [
            static::NEW,
            static::PENDING,
            static::PAID,
            static::CANCELLED,
        ];
    }

    public static function createState(string $state, Order $order): OrderState
    {
        return match ($state) {
            static::NEW => new NewOrderState($order),
            static::PENDING => new PendingOrderState($order),
            static::PAID => new PaidOrderState($order),
            static::CANCELLED => new CancelledOrderState($order)
        };
    }
}
