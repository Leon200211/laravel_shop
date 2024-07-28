<?php

namespace Tests\Feature;

use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    /**
     * @test Тестирует регистрацию пользователя
     *
     * @return void
     */
    public function is_store_success(): void
    {
        Event::fake();
        Notification::fake();

        $request = [
            'name' => 'Test',
            'email' => 'testing@cutcode.ru',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

        $response = $this->post(route('store'), $request);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
           'email' => $request['email']
        ]);

        $user = User::query()->where(['email' => $request['email']])->first();

        //Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $event = new Registered($user);
        $listener = new SendEmailNEwUserListener();
        $listener->handle($event);
        Notification::assertSentTo($user, NewUserNotification::class);

        $response->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }
}
