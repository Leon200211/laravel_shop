<?php

namespace Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_success_user_created(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => 'test@yandex.ru',
        ]);

        $action = app(RegisterNewUserContract::class);
        $action(NewUserDto::make('test', 'test@yandex.ru', 'root'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@yandex.ru',
        ]);
    }
}
