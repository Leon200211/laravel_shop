<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        $brands = Brand::query()->select(['id', 'title'])->has('products')->get();
        $categories = Category::query()->select(['id', 'title', 'slug'])->has('products')->get();
        $products = Product::search(request('search'))->query(function (Builder $query) use ($category) {
            $query->select(['id', 'title', 'slug', 'thumbnail', 'price'])
                ->when($category->exists, function (Builder $query) use ($category) {
                    $query->whereRelation('categories', 'categories.id', '=', $category->id);
                })->filtered()->sorted();
        })->paginate(9);

        return view('catalog.index', [
           'products' => $products,
           'categories' => $categories,
           'brands' => $brands,
           'category' => $category
        ]);
    }
}
