<?php

namespace Domain\Order\Processes;

use Domain\Cart\Models\CartItem;
use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderItem;

class AssignOptionValues implements OrderProcessContract
{
    public function handle(Order $order, $next)
    {
        $cartItems = cart()->items();

        /** @var OrderItem $item */
        foreach ($order->orderItems as $item) {
            $cartItem = $cartItems
                ->filter(fn (CartItem $cartItem) => $cartItem->product_id == $item->product_id)
                ->first()
            ;

            if (!$cartItem) {
                continue;
            }

            $item->optionValues()->sync($cartItem->optionValues);
        }

        return $next($order);
    }
}
