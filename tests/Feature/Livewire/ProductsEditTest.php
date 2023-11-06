<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ProductsEdit;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ProductsEditTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        $product = Product::factory()->create();

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->get('/products/'.$product->id.'/edit')
            ->assertSeeLivewire(ProductsEdit::class);
    }

    /** @test */
    public function by_default_sets_a_products_original_properties()
    {
        Category::factory(10)->create();

        $categories = collect(Category::pluck('id'));
        $product = Product::factory()->create([
            'name' => 'Not New Name',
            'description' => 'Not New Description',
            'colour' => 'Red',
            'in_stock' => true,
        ]);

        $product->categories()->sync($categories->random(2));

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->assertSet('form.name', 'Not New Name')
            ->assertSet('form.description', 'Not New Description')
            ->assertSet('form.colour', 'Red')
            ->assertSet('form.in_stock', true)
            ->assertSet('form.productCategories', $product->categories()->pluck('id')->toArray());
    }

    /** @test */
    public function can_update_product_properties()
    {
        $categories = Category::factory(2)->create();
        $newCategories = Category::factory(3)->create();
        $product = Product::factory()->create();

        $product->categories()->sync($categories->pluck('id'));

        $this->assertEquals(1, Product::count());
        $this->assertDatabaseCount('category_product', 2);

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->set('form.name', 'New Name')
            ->set('form.description', 'New Description')
            ->set('form.colour', 'Green')
            ->set('form.in_stock', false)
            ->set('form.productCategories', $newCategories->pluck('id')->toArray())
            ->assertSet('form.name', 'New Name')
            ->assertSet('form.description', 'New Description')
            ->assertSet('form.colour', 'Green')
            ->assertSet('form.in_stock', false)
            ->assertSet('form.productCategories', $newCategories->pluck('id')->toArray())
            ->call('save');

        $this->assertEquals(1, Product::count());
        $this->assertDatabaseCount('category_product', 3);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'description' => 'New Description',
            'colour' => 'Green',
            'in_stock' => false,
        ]);
    }

    /** @test */
    public function redirected_to_all_products_after_updating_a_product()
    {
        $categories = Category::factory(10)->create();
        $product = Product::factory()->create();

        $product->categories()->sync($categories->pluck('id'));

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->set('form.name', 'New Name')
            ->set('form.description', 'New Description')
            ->set('form.colour', 'Green')
            ->set('form.in_stock', false)
            ->set('form.productCategories', [$categories[0]->id])
            ->call('save')
            ->assertRedirect('/products');
    }

    /** @test */
    public function fields_are_required()
    {
        $product = Product::factory()->create();

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->set('form.name', '')
            ->set('form.description', '')
            ->set('form.colour', '')
            ->set('form.productCategories')
            ->call('save')
            ->assertHasErrors('form.name')
            ->assertHasErrors('form.description')
            ->assertHasErrors('form.colour')
            ->assertHasErrors('form.productCategories');
    }
}
