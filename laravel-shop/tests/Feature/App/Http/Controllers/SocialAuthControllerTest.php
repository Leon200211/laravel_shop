<?php

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;
use DomainException;
use Mockery\MockInterface;
use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Auth\SocialAuthController;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private function mockSocialiteCallback(string|int $githubId): MockInterface
    {
        $user = $this->mock(SocialiteUser::class, function (MockInterface $m) use ($githubId) {
            $m->shouldReceive('getId')->once()->andReturn($githubId);
            $m->shouldReceive('getNickname')->once()->andReturn(str()->random(10));
            $m->shouldReceive('getEmail')->once()->andReturn('test@yandex.com');
        });

        Socialite::shouldReceive('driver->user')->once()->andReturn($user);

        return $user;
    }

    private function callbackRequest(): TestResponse
    {
        return $this->get(
            action(
                [SocialAuthController::class, 'callback'],
                ['driver' => 'github']
            )
        );
    }

    /**
     * @test
     * @return void
     */
    public function it_github_redirect_success(): void
    {
        $this->get(
            action(
                [SocialAuthController::class, 'redirect'],
                ['driver' => 'github']
            )
        )->assertRedirectContains('github.com');
    }

    /**
     * @test
     * @return void
     */
    public function it_driver_not_found_exception(): void
    {
        $this->expectException(DomainException::class);

        $this->withoutExceptionHandling()
            ->get(action(
                    [SocialAuthController::class, 'redirect'],
                    ['driver' => 'vk']
            ))
        ;

        $this->withoutExceptionHandling()
            ->get(action(
                    [SocialAuthController::class, 'callback'],
                    ['driver', 'vk']
            ))
        ;
    }

    /**
     * @test
     * @return void
     */
    public function it_github_callback_created_user_success(): void
    {
        $githubId = str()->random(10);
        $this->assertDatabaseMissing('users', [
            'github_id' => $githubId
        ]);

        $this->mockSocialiteCallback($githubId);
        $this->callbackRequest()->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'github_id' => $githubId
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_authenticated_by_existing_user(): void
    {
        $githubId = str()->random(10);
        UserFactory::new()->create([
            'github_id' => $githubId
        ]);

        $this->assertDatabaseHas('users', [
            'github_id' => $githubId
        ]);

        $this->mockSocialiteCallback($githubId);
        $this->callbackRequest()->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }
}
