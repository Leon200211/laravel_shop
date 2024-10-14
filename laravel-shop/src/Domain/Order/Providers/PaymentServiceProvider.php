<?php

namespace Domain\Order\Providers;

use Domain\Order\Models\Payment;
use Domain\Order\Payment\Gateways\YooKassa;
use Domain\Order\Payment\PaymentData;
use Domain\Order\Payment\PaymentSystem;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        PaymentSystem::provider(new YooKassa());

        PaymentSystem::onCreating(function (PaymentData $paymentData) { return $paymentData; });
        PaymentSystem::onSuccess(function (Payment $payment) {});
        PaymentSystem::onError(function (string $message, Payment $payment) {});
    }

    public function register(): void
    {

    }
}
