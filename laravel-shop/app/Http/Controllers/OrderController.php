<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use Domain\Order\Actions\NewOrderAction;
use Domain\Order\DTOs\OrderCustomerDTO;
use Domain\Order\DTOs\OrderDTO;
use Domain\Order\Models\DeliveryType;
use Domain\Order\Models\PaymentMethod;
use Domain\Order\Processes\AssignCustomer;
use Domain\Order\Processes\AssignOptionValues;
use Domain\Order\Processes\AssignProducts;
use Domain\Order\Processes\ChangeStateToPending;
use Domain\Order\Processes\CheckProductQuantities;
use Domain\Order\Processes\ClearCart;
use Domain\Order\Processes\DecreaseProductsQuantities;
use Domain\Order\Processes\OrderProcess;
use DomainException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class OrderController extends Controller
{
    public function index(): Factory|View|Application
    {
        $items = cart()->items();

        if ($items->isEmpty()) {
            throw new DomainException('Корзина пуста');
        }

        return view('order.index', [
            'items' => $items,
            'payments' => PaymentMethod::query()->get(),
            'deliveries' => DeliveryType::query()->get()
        ]);
    }

    /**
     * @throws Throwable
     */
    public function handle(OrderFormRequest $request, NewOrderAction $action): RedirectResponse
    {
        $customer = OrderCustomerDTO::fromArray($request->get('customer'));

        $order = $action(
            OrderDTO::make(...$request->only(['payment_method_id', 'delivery_type_id', 'password'])),
            $customer,
            $request->boolean('create_account')
        );

        (new OrderProcess($order))->processes([
            new CheckProductQuantities(),
            new AssignCustomer($customer),
            new AssignProducts(),
            new AssignOptionValues(),
            new ChangeStateToPending(),
            new DecreaseProductsQuantities(),
            new ClearCart()
        ])->run();

        return redirect()->route('home');
    }
}
