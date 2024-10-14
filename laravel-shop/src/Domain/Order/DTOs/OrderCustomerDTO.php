<?php

namespace Domain\Order\DTOs;

use Support\Traits\Makeable;

class OrderCustomerDTO
{
    use Makeable;

    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $city,
        public readonly string $address
    ) {
    }

    public static function fromArray(array $customer): static
    {
        return static::make(
            $customer['first_name'] ?? '',
            $customer['last_name'] ?? '',
            $customer['email'] ?? '',
            $customer['phone'] ?? '',
            $customer['city'] ?? '',
            $customer['address'] ?? ''
        );
    }

    public function fullName(): string
    {
        return implode(' ', array_filter([$this->first_name, $this->last_name]));
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'address' => $this->address
        ];
    }
}
