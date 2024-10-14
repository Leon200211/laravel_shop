<?php

namespace Domain\Order\States\Payment;

class PaidPaymentState extends PaymentState
{
    public static string $name = 'paid';
}
