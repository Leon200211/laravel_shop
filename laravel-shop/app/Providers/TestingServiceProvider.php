<?php

namespace App\Providers;

use Faker\{Factory, Generator};
use Illuminate\Support\ServiceProvider;
use Support\Testing\FakerImageProvider;

class TestingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new FakerImageProvider($faker));

            return $faker;
        });
    }
}
