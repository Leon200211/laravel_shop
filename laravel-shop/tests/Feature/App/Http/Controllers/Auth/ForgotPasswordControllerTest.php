<?php

namespace App\Http\Controllers\Auth;

use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private function testingCredentials(): array
    {
        return [
            'email' => 'test@yandex.ru'
        ];
    }

    /**
     * @test Тестирует переход на страницу восстановление пароля
     *
     * @return void
     */
    public function it_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()->assertViewIs('auth.forgot-password');
    }

    /**
     * @test Тестирует восстановление пароля
     *
     * @return void
     */
    public function it_handle_success(): void
    {
        Notification::fake();

        $user = UserFactory::new()->create($this->testingCredentials());
        $this->post(action([ForgotPasswordController::class, 'handle']), $this->testingCredentials())->assertRedirect();

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    /**
     * @test Тестирует попытку восстановить пароль несуществующего пользователя
     *
     * @return void
     */
    public function it_handle_fail(): void
    {
        Notification::fake();

        $this->assertDatabaseMissing('users', $this->testingCredentials());
        $this->post(action([ForgotPasswordController::class, 'handle']), $this->testingCredentials())->assertInvalid(['email']);

        Notification::assertNothingSent();
    }
}
