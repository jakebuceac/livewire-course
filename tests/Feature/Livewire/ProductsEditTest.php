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
        Category::factory()->create();

        $product = Product::factory()->create();

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->assertStatus(200);
    }

    /** @test */
    public function component_exists_on_the_page()
    {
        Category::factory()->create();

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->get('/products/'.$product->id.'/edit')
            ->assertSeeLivewire(ProductsEdit::class);
    }

    /** @test */
    public function by_default_sets_a_products_original_properties()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Not New Name',
            'description' => 'Not New Description',
        ]);

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->assertSet('form.name', 'Not New Name')
            ->assertSet('form.description', 'Not New Description')
            ->assertSet('form.category_id', $category->id);
    }

    /** @test */
    public function can_update_product_properties()
    {
        Category::factory()->create();

        $product = Product::factory()->create();
        $newCategory = Category::factory()->create();

        $this->assertEquals(1, Product::count());

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->set('form.name', 'New Name')
            ->set('form.description', 'New Description')
            ->set('form.category_id', $newCategory->id)
            ->assertSet('form.name', 'New Name')
            ->assertSet('form.description', 'New Description')
            ->assertSet('form.category_id', $newCategory->id)
            ->call('save');

        $this->assertEquals(1, Product::count());
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $newCategory->id,
            'name' => 'New Name',
            'description' => 'New Description',
        ]);
    }

    /** @test */
    public function redirected_to_all_products_after_updating_a_product()
    {
        Category::factory()->create();

        $product = Product::factory()->create();
        $newCategory = Category::factory()->create();

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->set('form.name', 'New Name')
            ->set('form.description', 'New Description')
            ->set('form.category_id', $newCategory->id)
            ->call('save')
            ->assertRedirect('/products');
    }

    /** @test */
    public function fields_are_required()
    {
        Category::factory()->create();

        $product = Product::factory()->create();

        Livewire::test(ProductsEdit::class, ['product' => $product])
            ->set('form.name', '')
            ->set('form.description', '')
            ->set('form.category_id', '')
            ->call('save')
            ->assertHasErrors('form.name')
            ->assertHasErrors('form.description')
            ->assertHasErrors('form.category_id');
    }
}
