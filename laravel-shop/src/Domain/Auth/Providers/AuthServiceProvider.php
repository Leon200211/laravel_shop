<?php

namespace Domain\Auth\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        //
    }

    public function register()
    {
        $this->app->register(
            ActionsServiceProvider::class,
        );
    }
}
