<?php

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class SignUpFormRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $password = $this->faker->password(8);

        return [
           'email'                  => $this->faker->email,
            'name'                  => $this->faker->name,
            'password'              => $password,
            'password_confirmation' => $password,
        ];
    }
}
