<?php

namespace App\Livewire;

use App\Livewire\Forms\ProductsForm;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class ProductsCreate extends ModalComponent
{
    use WithFileUploads;

    public ProductsForm $form;

    public Collection $categories;

    public function mount(): void
    {
        $this->categories = Category::pluck('name', 'id');
    }

    public function save(): void
    {
        $this->form->save();

        $this->redirect('/products');
    }

    public function render(): View
    {
        return view('livewire.products-create');
    }
}
