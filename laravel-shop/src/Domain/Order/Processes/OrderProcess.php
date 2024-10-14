<?php

namespace Domain\Order\Processes;

use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Events\OrderCreated;
use Domain\Order\Models\Order;
use DomainException;
use Illuminate\Pipeline\Pipeline;
use Support\Transaction;
use Throwable;

class OrderProcess
{
    protected array $processes = [];

    public function __construct(protected Order $order) {}

    public function processes(array $processes): static
    {
        $this->processes = array_filter($processes, fn ($process) => $process instanceof OrderProcessContract);

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function run(): Order
    {
        return Transaction::run(function () {
            return app(Pipeline::class)
                ->send($this->order)
                ->through($this->processes)
                ->thenReturn()
            ;
        }, function (Order $order) {
            flash()->info('Good # ' . $order->id);

            event(new OrderCreated($order));
        }, function (Throwable $exception) {
            throw new DomainException(!app()->isProduction()
                ? $exception->getMessage()
                : 'Не удалось оформить заказ! Пожалуйста, попробуйте позже'
            );
        });
    }
}
