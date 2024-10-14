<?php

namespace Domain\Order\Providers;

use Illuminate\Support\ServiceProvider;
class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

    }

    public function register()
    {
        $this->app->register(
            ActionsServiceProvider::class,
        );
    }
}
