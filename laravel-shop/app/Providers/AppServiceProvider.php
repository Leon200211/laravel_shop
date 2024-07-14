<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());
        // возвращает исключение если мы не используем ленивую загрузку
//        Model::preventLazyLoading(!app()->isProduction());
        // возвращает исключение если атрибута нет в филибл поле
//        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {
            logger()
                ->channel('telegram')
                ->debug('laravel-shop whenQueryingForLongerThan:' . $connection->query()->toSql())
            ;
        });

        DB::listen(function ($query) {
            if ($query->time > 100) {
                logger()
                    ->channel('telegram')
                    ->debug('laravel-shop whenQueryingLongerThan:' . $query->sql, $query->bindings)
                ;
            }
        });

        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(4),
            function () {
                logger()
                    ->channel('telegram')
                    ->debug('laravel-shop whenRequestLifecycleIsLongerThan:' . request()->url())
                ;
            }
        );
    }
}
