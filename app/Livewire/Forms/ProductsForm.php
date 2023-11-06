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

    #[Rule('image')]
    public $image;

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

        $filename = $this->image->store('products');
        $product = Product::create($this->all() + ['photo' => $filename]);

        $product->categories()->sync($this->productCategories);
    }

    public function update(): void
    {
        $this->validate();

        $filename = $this->image->store('products', 'public');

        $this->product->update($this->all() + ['photo' => $filename]);
        $this->product->categories()->sync($this->productCategories);
    }
}
