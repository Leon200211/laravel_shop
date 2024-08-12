<?php

namespace App\Http\Controllers;

use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_success_response(): void
    {
        // TODO переписать под фабрику тестов
        CategoryFactory::new()->count(5)->create([
            'on_home_page' => true,
            'sorting' => 999,
        ]);

        $firstCategory = CategoryFactory::new()->create([
            'on_home_page' => true,
            'sorting' => 1,
        ]);

        ProductFactory::new()->count(5)->create([
            'on_home_page' => true,
            'sorting' => 999,
        ]);

        $firstProduct = ProductFactory::new()->create([
            'on_home_page' => true,
            'sorting' => 1,
        ]);

        BrandFactory::new()->count(5)->create([
            'on_home_page' => true,
            'sorting' => 999,
        ]);

        $firstBrand = BrandFactory::new()->create([
            'on_home_page' => true,
            'sorting' => 1,
        ]);

        $this->get(action(HomeController::class))
            ->assertOk()
            ->assertViewHas('categories.0', $firstCategory)
            ->assertViewHas('products.0', $firstProduct)
            ->assertViewHas('brands.0', $firstBrand)
        ;
    }
}
