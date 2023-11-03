<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ProductsCreate;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ProductsCreateTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ProductsCreate::class)
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/products/create')
            ->assertSeeLivewire(ProductsCreate::class);
    }

    /** @test */
    public function can_set_product_properties()
    {
        $this->assertEquals(0, Product::count());

        $category = Category::factory()->create();

        Livewire::test(ProductsCreate::class)
            ->set('name', 'Test Name')
            ->set('description', 'Confessions of a serial soaker')
            ->set('category_id', $category->id)
            ->assertSet('name', 'Test Name')
            ->assertSet('description', 'Confessions of a serial soaker')
            ->assertSet('category_id', $category->id)
            ->call('save');

        $this->assertEquals(1, Product::count());
    }
}
