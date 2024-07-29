<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
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
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.index')
        ;
    }

    /**
     * @test Тестирует переход на страницу регистрации
     *
     * @return void
     */
    public function it_sign_up_page_success(): void
    {
        $this->get(action([AuthController::class, 'signUp']))
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
        $this->get(action([AuthController::class, 'forgot']))
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
        $user = User::factory()->create([
            'email' => 'test@yandex.ru',
        ]);

        $this->actingAs($user)->delete(action([AuthController::class, 'logOut']));
        $this->assertGuest();
    }

    /**
     * @test Тестирует вход пользователя
     *
     * @return void
     */
    public function it_sign_up_success(): void
    {
        $email = 'test@yandex.ru';
        $password = '123';
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);
        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password,
        ]);
        $response = $this->post(action([AuthController::class, 'signIn']), $request);
        $response->assertValid()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test Тестирует регистрацию пользователя
     *
     * @return void
     */
    public function it_store_success(): void
    {
        Event::fake();
        Notification::fake();

        $request = SignUpFormRequest::factory()->create();
        //$response = $this->post(route('store'), $request);
        $response = $this->post(action([AuthController::class, 'store']), $request);

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
