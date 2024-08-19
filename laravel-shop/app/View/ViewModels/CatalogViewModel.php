<?php

namespace App\View\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Spatie\ViewModels\ViewModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CatalogViewModel extends ViewModel
{
    public function __construct(
        public Category $category
    )
    {
    }

    public function products(): LengthAwarePaginator
    {
        return Product::search(request('search'))->query(function (Builder $query) {
            $query->select(['id', 'title', 'slug', 'thumbnail', 'price', 'json_properties'])
                ->withCategory($this->category)->filtered()->sorted();
        })->paginate(9);
    }

    public function categories(): Collection|array
    {
        return Category::query()->select(['id', 'title', 'slug'])->has('products')->get();
    }
}
