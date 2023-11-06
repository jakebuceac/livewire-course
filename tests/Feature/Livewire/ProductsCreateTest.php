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
            ->set('form.name', 'Test Name')
            ->set('form.description', 'Confessions of a serial soaker')
            ->set('form.category_id', $category->id)
            ->set('form.colour', 'Red')
            ->set('form.in_stock', true)
            ->assertSet('form.name', 'Test Name')
            ->assertSet('form.description', 'Confessions of a serial soaker')
            ->assertSet('form.category_id', $category->id)
            ->assertSet('form.colour', 'Red')
            ->assertSet('form.in_stock', true)
            ->call('save');

        $this->assertEquals(1, Product::count());
    }

    /** @test */
    public function redirected_to_all_products_after_creating_a_product()
    {
        $category = Category::factory()->create();

        Livewire::test(ProductsCreate::class)
            ->set('form.name', 'New Name')
            ->set('form.description', 'New Description')
            ->set('form.category_id', $category->id)
            ->set('form.colour', 'Red')
            ->set('form.in_stock', true)
            ->call('save')
            ->assertRedirect('/products');
    }

    /** @test */
    public function fields_are_required()
    {
        Livewire::test(ProductsCreate::class)
            ->set('form.name', '')
            ->set('form.description', '')
            ->set('form.category_id', '')
            ->set('form.colour', '')
            ->call('save')
            ->assertHasErrors('form.name')
            ->assertHasErrors('form.description')
            ->assertHasErrors('form.category_id')
            ->assertHasErrors('form.colour');
    }
}
