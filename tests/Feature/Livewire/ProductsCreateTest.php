<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ProductsCreate;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        Storage::fake('public');

        $image = UploadedFile::fake()->image('photo.jpg');
        $categories = Category::factory()
            ->count(5)
            ->create();

        $this->assertEquals(0, Product::count());

        Livewire::test(ProductsCreate::class)
            ->set('form.name', 'Test Name')
            ->set('form.description', 'Confessions of a serial soaker')
            ->set('form.colour', 'Red')
            ->set('form.in_stock', true)
            ->set('form.image', $image)
            ->set('form.productCategories', $categories->pluck('id')->toArray())
            ->assertSet('form.name', 'Test Name')
            ->assertSet('form.description', 'Confessions of a serial soaker')
            ->assertSet('form.colour', 'Red')
            ->assertSet('form.in_stock', true)
            ->assertSet('form.productCategories', $categories->pluck('id')->toArray())
            ->call('save');

        $this->assertEquals(1, Product::count());
        $this->assertDatabaseCount('category_product', 5);
    }

    /** @test */
    public function redirected_to_all_products_after_creating_a_product()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('photo.jpg');
        $categories = Category::factory()
            ->count(5)
            ->create();

        Livewire::test(ProductsCreate::class)
            ->set('form.name', 'Test Name')
            ->set('form.description', 'Confessions of a serial soaker')
            ->set('form.colour', 'Red')
            ->set('form.in_stock', true)
            ->set('form.image', $image)
            ->set('form.productCategories', $categories->pluck('id')->toArray())
            ->call('save')
            ->assertRedirect('/products');
    }

    /** @test */
    public function fields_are_required()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.txt');

        Livewire::test(ProductsCreate::class)
            ->set('form.name', '')
            ->set('form.description', '')
            ->set('form.colour', '')
            ->set('form.image', $file)
            ->set('form.productCategories')
            ->call('save')
            ->assertHasErrors('form.name')
            ->assertHasErrors('form.description')
            ->assertHasErrors('form.colour')
            ->assertHasErrors('form.image')
            ->assertHasErrors('form.productCategories');
    }
}
