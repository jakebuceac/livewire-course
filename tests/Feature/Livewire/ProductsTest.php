<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Products;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(Products::class)
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/products')
            ->assertSeeLivewire(Products::class);
    }

    /** @test */
    public function displays_products()
    {
        Category::factory()->create();

        Product::factory()->count(2)
            ->state(new Sequence(
                ['name' => 'product_1'],
                ['name' => 'product_2'],
            ))
            ->create();

        Livewire::test(Products::class)
            ->assertSee('product_1')
            ->assertSee('product_2');
    }

    /** @test */
    public function can_delete_product()
    {
        Category::factory()->create();

        $product = Product::factory()->create();

        $this->assertEquals(1, Product::count());

        Livewire::test(Products::class)
            ->set('name', $product->name)
            ->set('description', $product->description)
            ->call('deleteProduct', $product->id);

        $this->assertEquals(0, Product::count());
    }
}
