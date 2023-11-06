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

    #[Rule('required|exists:categories,id', as: 'category')]
    public int $category_id;

    #[Rule('required|string')]
    public string $colour = '';

    #[Rule('boolean')]
    public bool $in_stock = true;

    public function setProduct(Product $product): void
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->colour = $product->colour;
        $this->in_stock = $product->in_stock;
    }

    public function save(): void
    {
        $this->validate();

        Product::create($this->all());
    }

    public function update(): void
    {
        $this->validate();

        $this->product->update($this->all());
    }
}
