<?php

namespace Domain\Order\Payment;

use Closure;
use Domain\Order\Contracts\PaymentGatewayContract;
use Domain\Order\Exceptions\PaymentProcessException;
use Domain\Order\Exceptions\PaymentProviderException;
use Domain\Order\Models\Payment;
use Domain\Order\Models\PaymentHistory;
use Domain\Order\States\Payment\PaidPaymentState;
use Domain\Order\Traits\PaymentEvents;

class PaymentSystem
{
    use PaymentEvents;

    protected static PaymentGatewayContract $provider;

    /**
     * @throws PaymentProviderException
     */
    public static function provider(PaymentGatewayContract|Closure $providerOrClosure): void
    {
        if (is_callable($providerOrClosure)) {
            $providerOrClosure = call_user_func($providerOrClosure);
        }

        if (!$providerOrClosure instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        static::$provider = $providerOrClosure;
    }

    /**
     * @throws PaymentProviderException
     */
    public static function create(PaymentData $paymentData): PaymentGatewayContract
    {
        if (!static::$provider instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        Payment::query()->create([
            'payment_id' => $paymentData->id
        ]);

        if (is_callable(static::$onCreating)) {
            $paymentData = call_user_func(static::$onCreating, $paymentData);
        }

        return static::$provider->data($paymentData);
    }

    /**
     * @throws PaymentProviderException
     */
    public static function validate(): PaymentGatewayContract
    {
        if (!static::$provider instanceof PaymentGatewayContract) {
            throw PaymentProviderException::providerRequired();
        }

        PaymentHistory::query()->create([
            'method' => request()->method(),
            'payload' => static::$provider->request(),
            'payment_gateway' => get_class(static::$provider)
        ]);

        if (is_callable(static::$onValidating)) {
            call_user_func(static::$onValidating);
        }

        if (static::$provider->validate() && static::$provider->paid()) {
            try {
                $payment = Payment::query()
                    ->where('payment_id', static::$provider->paymentId())
                    ->firstOr(function () {
                        throw PaymentProcessException::paymentNotFound();
                    });

                if (is_callable(static::$onSuccess)) {
                    call_user_func(static::$onSuccess, $payment);
                }

                $payment->state->transitionTo(PaidPaymentState::class);
            } catch (PaymentProcessException $ex) {
                if (is_callable(static::$onError)) {
                    call_user_func(
                        static::$onError,
                        static::$provider->errorMessage() ?? $ex->getMessage()
                    );
                }
            }
        }

        return static::$provider;
    }
}
