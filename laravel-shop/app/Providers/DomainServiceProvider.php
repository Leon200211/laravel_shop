<?php

namespace App\Providers;

use Domain\Auth\Providers\AuthServiceProvider;

use Domain\Cart\Providers\CartServiceProvider;
use Domain\Order\Providers\OrderServiceProvider;
use Domain\Product\Providers\ProductServiceProvider;
use Illuminate\Support\ServiceProvider;
use Domain\Catalog\Providers\CatalogServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(CatalogServiceProvider::class);
        $this->app->register(ProductServiceProvider::class);
        $this->app->register(CartServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
    }
}
