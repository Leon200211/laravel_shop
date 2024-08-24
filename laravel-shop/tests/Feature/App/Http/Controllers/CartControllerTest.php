<?php

namespace App\Http\Controllers;

use Database\Factories\ProductFactory;
use Domain\Cart\CartManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CartManager::fake();
    }

    /**
     * @test
     * @return void
     */
    public function it_is_empty_cart(): void
    {
        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewis('cart.index')
            ->assertViewHas('items', collect())
        ;
    }

    /**
     * @test
     * @return void
     */
    public function it_is_not_empty_cart(): void
    {
        $product = $this->createProduct();

        cart()->add($product);

        $this->get(action([CartController::class, 'index']))
            ->assertOk()
            ->assertViewis('cart.index')
            ->assertViewHas('items', cart()->items())
        ;
    }

    /**
     * @test
     * @return void
     */
    public function it_added_success(): void
    {
        $product = $this->createProduct();

        $this->assertEquals(0, cart()->count());

        $this->post(action([CartController::class, 'add'], $product), ['quantity' => 4]);

        $this->assertEquals(4, cart()->count());
    }

    /**
     * @return void
     */
    public function it_quantity_changed(): void
    {
        $product = $this->createProduct();

        cart()->add($product, 4);

        $this->assertEquals(4, cart()->count());

        $this->post(action([CartController::class, 'quantity'], cart()->items()->first()), ['quantity' => 8]);

        $this->assertEquals(8, cart()->count());
    }

    /**
     * @test
     * @return void
     */
    public function it_delete_success(): void
    {
        $product = $this->createProduct();

        cart()->add($product, 4);

        $this->assertEquals(4, cart()->count());

        $this->delete(action([CartController::class, 'delete'], cart()->items()->first()));

        $this->assertEquals(0, cart()->count());
    }

    /**
     * @test
     * @return void
     */
    public function it_truncate_success(): void
    {
        $product = $this->createProduct();

        cart()->add($product, 4);

        $this->assertEquals(4, cart()->count());

        $this->delete(action([CartController::class, 'truncate']));

        $this->assertEquals(0, cart()->count());
    }

    private function createProduct()
    {
        return ProductFactory::new()->create();
    }
}
