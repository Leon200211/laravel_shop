<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = SignUpFormRequest::factory()->create([
            'email' => 'test@yandex.ru',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ]);
    }

    private function request(): TestResponse
    {
        return $this->post(
            action([SignUpController::class, 'handle']),
            $this->request
        );
    }

    private function findUser(): User
    {
        return User::query()
            ->where('email', $this->request['email'])
            ->first()
        ;
    }

    /**
     * @test Тестирует доступ к странице регистрации
     *
     * @return void
     */
    public function it_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up')
        ;
    }

    /**
     * @test Тестирует валидность запроса регистрации
     *
     * @return void
     */
    public function it_validation_success(): void
    {
        $this->request()->assertValid();
    }

    /**
     * @test Тестирует регистрацию с неверными данными
     *
     * @return void
     */
    public function it_should_fail_validation_on_password_confirm(): void
    {
        $this->request['password'] = '123';
        $this->request['password_confirmation'] = '1234';

        $this->request()->assertInvalid(['password']);
    }

    /**
     * @test Тестирует регистрацию пользователя
     *
     * @return void
     */
    public function it_user_created_success(): void
    {
        $this->assertDatabaseMissing('users', [
            'email' => $this->request['email']
        ]);

        $this->request();

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email']
        ]);
    }

    /**
     * @test Тестирует регистрацию пользователя с повторением почты
     *
     * @return void
     */
    public function it_should_fail_validation_on_unique_email(): void
    {
        UserFactory::new()->create([
            'email' => $this->request['email']
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->request['email']
        ]);

        $this->request()->assertInvalid(['email']);
    }

    /**
     * @test Тестирует событие регистрации
     *
     * @return void
     */
    public function it_registered_event_and_listeners_dispatched(): void
    {
        Event::fake();

        $this->request();

        Event::assertDispatched(Registered::class);
        Event::assertListening(
            Registered::class,
            SendEmailNewUserListener::class
        );
    }

    /**
     * @test Тестирует редирект на главную страницу и авторизацию под нужным пользователем
     *
     * @return void
     */
    public function it_user_authenticated_after_and_redirected(): void
    {
        $this->request()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($this->findUser());
    }
}
