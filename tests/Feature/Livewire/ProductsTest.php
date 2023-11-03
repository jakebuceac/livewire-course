<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Products;
use App\Models\User;
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
        $user = User::factory()
            ->create();

        $this->actingAs($user)
            ->get('/products')
            ->assertSeeLivewire(Products::class);
    }
}
