<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $categories = collect(Category::pluck('id'));

        return [
            'category_id' => $categories->random(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(50),
            'colour' => $this->faker->randomElement(Product::COLOR_LIST),
            'in_stock' => $this->faker->boolean(),
        ];
    }
}
