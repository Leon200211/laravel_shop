<?php

namespace Support\ValueObjects;


use InvalidArgumentException;
use Stringable;
use Support\Traits\Makeable;

final class Price implements Stringable
{
    use Makeable;

    private array $currencies = [
        'RUB' => '₽'
    ];

    public function __construct(
//        private readonly int $value,
//        private readonly string $currency = 'RUB',
//        private readonly int $precision = 100

        private int $value,
        private string $currency = 'RUB',
        private int $precision = 100
    )
    {
        if ($this->value < 0) {
            throw new InvalidArgumentException('Price must be more than zero');
        }

        if (!array_key_exists($this->currency, $this->currencies)) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

    public function value(): float|int
    {
        return $this->value / $this->precision;
    }

    public function raw(): int
    {
        return $this->value;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function currencySymbol(): string
    {
        return $this->currencies[$this->currency];
    }

    public function __toString(): string
    {
        return number_format($this->value(), 0, ',', ' ') . ' ' . $this->currencySymbol();
    }
}
