<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.products', [
            'products' => Product::paginate(10),
        ]);
    }
}
