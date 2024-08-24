<?php

namespace App\Http\Controllers;

use Domain\Cart\Models\CartItem;
use Domain\Product\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Foundation\Application;

class CartController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('cart.index', [
            'items' => cart()->items()
        ]);
    }

    public function add(Product $product): RedirectResponse
    {
        cart()->add(
            $product,
            request('quantity', 1),
            request('options', [])
        );
        flash()->info('Товар добавлен к корзину!' );

        return redirect()->intended(route('cart'));
    }

    public function quantity(CartItem $item): RedirectResponse
    {
        cart()->quantity($item, request('quantity', 1));
        flash()->info('Количество товаров изменено!');

        return redirect()->intended(route('cart'));
    }

    public function delete(CartItem $item): RedirectResponse
    {
        cart()->delete($item);
        flash()->info('Товар удален!');

        return redirect()->intended(route('cart'));
    }

    public function truncate(): RedirectResponse
    {
        cart()->truncate();
        flash()->info('Корзина отчищина!');

        return redirect()->intended(route('cart'));
    }
}
