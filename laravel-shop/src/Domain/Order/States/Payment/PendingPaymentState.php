<?php

namespace Domain\Order\States\Payment;

class PendingPaymentState extends PaymentState
{
    public static string $name = 'pending';
}
