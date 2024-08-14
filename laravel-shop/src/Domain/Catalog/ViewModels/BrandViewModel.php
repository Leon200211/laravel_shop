<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

final class BrandViewModel
{
    use Makeable;

    public function homePage(): Collection|array
    {
        // TODO добавить обсервер на обновление категирий чтобы сбросить кэш по ключу
        // tags(['category']) если переходить на нормальное кэширование
        return Cache::rememberForever('brand_home_page', function () {
            return Brand::query()->homePage()->get();
        });
    }
}
