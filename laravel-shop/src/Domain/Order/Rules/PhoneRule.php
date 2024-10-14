<?php

namespace Domain\Order\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return is_numeric($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Введите телефон (только цифры без знаков)';
    }
}
