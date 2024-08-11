<?php

namespace Domain\Auth\DTOs;

use Illuminate\Http\Request;
use Support\Traits\Makeable;

final class NewUserDto
{
    use Makeable;

    public function __construct(
//        public readonly string  $name,
//        public readonly string $email,
//        public readonly string $password,

        public string  $name,
        public string $email,
        public string $password,
    )
    {
    }

    public static function fromRequest(Request $request): NewUserDto
    {
        return new self(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );
    }
}
