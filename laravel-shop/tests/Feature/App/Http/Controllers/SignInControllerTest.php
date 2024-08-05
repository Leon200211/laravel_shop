<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendEmailNewUserListener;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test Тестирует доступ к странице авторизации
     *
     * @return void
     */
   public function it_page_success(): void
   {
       $this->get(action([SignInController::class, 'page']))
           ->assertOk()
           ->assertSee('Вход в аккаунт')
           ->assertViewIs('auth.login')
       ;
   }

    /**
     * @test Тестирует успешный вход в систему
     *
     * @return void
     */
   public function it_handle_success(): void
   {
       $password = '123456789';

       $user = UserFactory::new()->create([
           'email' => 'testing@yandex.ru',
           'password' => bcrypt($password)
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
     * @test Тестирует неудачный вход в систему
     *
     * @return void
     */
   public function it_handle_fail(): void
   {
       $request = SignInFormRequest::factory()->create([
           'email' => 'test@yandex.ru',
           'password' => str()->random(),
       ]);

       $this->post(action([SignInController::class, 'handle']), $request)->assertInvalid(['email']);
       $this->assertGuest();
   }

    /**
     * @test Тестирует выход из аккаунта
     *
     * @return void
     */
   public function it_logout_success(): void
   {
       $user = UserFactory::new()->create([
          'email' => 'testing@yandex.ru',
       ]);

       $this->actingAs($user)->delete(action([SignInController::class, 'logOut']));
       $this->assertGuest();
   }

    /**
     * @test Тестирует попытку выхода из аккаунта неавторизованного пользователя
     *
     * @return void
     */
   public function it_logout_guest_middelware_fail(): void
   {
       $this->delete(action([SignInController::class, 'logOut']))->assertRedirect(route('home'));
   }
}
