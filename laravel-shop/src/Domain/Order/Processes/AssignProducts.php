<?php

namespace Domain\Order\Processes;

use Domain\Cart\Models\CartItem;
use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Models\Order;
use Domain\Order\Models\OrderItem;

class AssignProducts implements OrderProcessContract
{

    public function handle(Order $order, $next)
    {
        $order->orderItems()
            ->createMany(
                cart()->items()->map(function (CartItem $item) {
                    return [
                        'product_id' => $item->product_id,
                        'price' => $item->price,
                        'quantity' => $item->quantity
                    ];
                })->toArray()
            )
        ;

        $order->update([
            'amount' => $order->orderItems->sum(
                fn (OrderItem $item) => $item->amount->raw()
            )
        ]);

        return $next($order);
    }
}
