<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(string $name, string $email, string $password)
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        event(new Registered($user));

        return $user;
    }
}
