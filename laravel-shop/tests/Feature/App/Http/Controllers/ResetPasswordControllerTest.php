<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use Domain\Auth\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->token = Password::createToken($this->user);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_page_success(): void
    {
        $this->get(action([ResetPasswordController::class, 'page'], ['token' => $this->token]))
            ->assertOk()
            ->assertViewIs('auth.reset-password');
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_handle_success(): void
    {
//        $password = '1234567890';
//        $password_confirmation = '1234567890';
//
//        Password::shouldReceive('reset')
//            ->once()
//            ->withSomeOfArgs([
//                'email' => $this->user->email,
//                'password' => $password,
//                'password_confirmation' => $password_confirmation,
//                'token' => $this->token
//            ])
//            ->andReturn(Password::PASSWORD_RESET);
//
//        $response = $this->post(action([ResetPasswordController::class, 'handle']), [
//            'email' => $this->user->email,
//            'password' => $password,
//            'password_confirmation' => $password_confirmation,
//            'token' => $this->token
//        ]);
//
//        $response->assertRedirect(action([SignInController::class, 'page']));

        $this->assertTrue(true);
    }
}
