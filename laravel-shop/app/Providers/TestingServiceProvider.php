<?php

namespace App\Providers;

use App\Support\Testing\FakerImageProvider;
use Faker\{Factory, Generator};
use Illuminate\Support\ServiceProvider;

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
