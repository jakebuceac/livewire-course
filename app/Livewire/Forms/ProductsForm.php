<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ProductsForm extends Form
{
    public ?Product $product;

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('required|min:3')]
    public string $description = '';

    #[Rule('required|string')]
    public string $colour = '';

    #[Rule('boolean')]
    public bool $in_stock = true;

    #[Rule('required|array', as: 'category')]
    public array $productCategories = [];

    public function setProduct(Product $product): void
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->colour = $product->colour;
        $this->in_stock = $product->in_stock;

        $this->productCategories = $product->categories()->pluck('id')->toArray();
    }

    public function save(): void
    {
        $this->validate();

        $product = Product::create($this->all());

        $product->categories()->sync($this->productCategories);
    }

    public function update(): void
    {
        $this->validate();

        $this->product->update($this->all());
        $this->product->categories()->sync($this->productCategories);
    }
}
