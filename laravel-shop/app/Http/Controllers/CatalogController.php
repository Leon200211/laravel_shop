<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        $categories = Category::query()->select(['id', 'title', 'slug'])->has('products')->get();
        $products = Product::search(request('search'))->query(function (Builder $query) use ($category) {
            $query->select(['id', 'title', 'slug', 'thumbnail', 'price', 'json_properties'])
               ->withCategory($category)->filtered()->sorted();
        })->paginate(9);

        return view('catalog.index', [
           'products' => $products,
           'categories' => $categories,
           'category' => $category
        ]);
    }
}
