<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::factory(10)->create();

        $categories = collect(Category::pluck('id'));

        Product::factory(50)
            ->create()
            ->each(function (Product $product) use ($categories) {
                $product->categories()->sync($categories->random(2));
            });
    }
}
