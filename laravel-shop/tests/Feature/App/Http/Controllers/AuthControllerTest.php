<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test Тестирует переход на страницу входа
     *
     * @return void
     */
    public function it_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login')
        ;
    }

    /**
     * @test Тестирует переход на страницу регистрации
     *
     * @return void
     */
    public function it_sign_up_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up')
        ;
    }

    /**
     * @test Тестирует переход на страницу восстановления пароля
     *
     * @return void
     */
    public function it_forgot_password_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password')
        ;
    }

    /**
     * @test Тестирует выход пользователя
     *
     * @return void
     */
    public function it_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'test@yandex.ru',
        ]);

        $this->actingAs($user)->delete(action([SignInController::class, 'logOut']));
        $this->assertGuest();
    }

    /**
     * @test Тестирует вход пользователя
     *
     * @return void
     */
    public function it_sign_in_success(): void
    {
        $email = 'test@yandex.ru';
        $password = '123';
        $user = UserFactory::new()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);
        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password,
        ]);
        $response = $this->post(action([SignInController::class, 'handle']), $request);
        $response->assertValid()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test Тестирует регистрацию пользователя
     *
     * @return void
     */
    public function it_sign_up_success(): void
    {
        Event::fake();
        Notification::fake();

        $request = SignUpFormRequest::factory()->create();
        //$response = $this->post(route('store'), $request);
        $response = $this->post(action([SignUpController::class, 'handle']), $request);

        $response->assertValid();
        $this->assertDatabaseHas('users', ['email' => $request['email']]);

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $user = User::query()->where(['email' => $request['email']])->first();
        $event = new Registered($user);
        $listener = new SendEmailNEwUserListener();
        $listener->handle($event);
        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);
    }
}
