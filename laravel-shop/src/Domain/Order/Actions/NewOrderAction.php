<?php

namespace Domain\Order\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Order\DTOs\OrderCustomerDTO;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\Order;

class NewOrderAction
{
    public function __invoke(
        OrderDTO $orderDTO,
        OrderCustomerDTO $customerDTO,
        bool $createAccount
    ): Order {
        if ($createAccount) {
            $reqisterAction = app(RegisterNewUserContract::class);

            $reqisterAction(NewUserDTO::make(
                $customerDTO->fullName(),
                $customerDTO->email,
                $orderDTO->password
            ));
        }

        return Order::query()->create([
            'user_id' => auth()?->id(),
            'payment_method_id' => $orderDTO->payment_method_id,
            'delivery_type_id' => $orderDTO->delivery_type_id,
        ]);
    }
}
