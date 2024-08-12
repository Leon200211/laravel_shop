<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

final class CategoryViewModel
{
    use Makeable;

    public function homePage(): Collection|array
    {
        // TODO добавить обсервер на обновление категирий чтобы сбросить кэш по ключу
        // tags(['category']) если переходить на нормальное кэширование
        return Cache::rememberForever('category_home_page', function () {
            return Category::query()->homePage()->get();
        });
    }
}
