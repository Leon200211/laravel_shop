<?php

namespace Domain\Order\States\Payment;

class CancelledPaymentState extends PaymentState
{
    public static string $name = 'failed';
}
