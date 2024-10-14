<?php

namespace App\Http\Requests;

use Domain\Order\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class OrderFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer.first_name' => ['required', 'string'],
            'customer.last_name' => ['required', 'string'],
            'customer.email' => ['required', 'email:dns'],
            'customer.phone' => ['required', new PhoneRule()],
            'customer.city' => ['sometimes'],
            'customer.address' => ['sometimes'],
            'create_account' => ['sometimes', 'bool'],
            'password' => request()->boolean('create_account')
                ? ['required', 'confirmed', Password::defaults()]
                : ['sometimes'],
            'delivery_type_id' => ['required', 'exists:delivery_types,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ];
    }
}
