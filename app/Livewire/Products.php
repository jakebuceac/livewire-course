<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public Collection $categories;

    public string $searchQuery = '';

    public int $searchCategory = 0;

    public function mount(): void
    {
        $this->categories = Category::pluck('name', 'id');
    }

    public function updating($key): void
    {
        if ($key === 'searchQuery' || $key === 'searchCategory') {
            $this->resetPage();
        }
    }

    public function deleteProduct(int $productId): void
    {
        Product::whereId($productId)->delete();
    }

    public function render(): View
    {
        sleep(1);

        $products = Product::with('category')
            ->when($this->searchQuery !== '', fn (Builder $query) => $query->where('name', 'like', '%'.$this->searchQuery.'%'))
            ->when($this->searchCategory > 0, fn (Builder $query) => $query->whereCategoryId($this->searchCategory))
            ->paginate(10);

        return view('livewire.products', [
            'products' => $products,
        ]);
    }
}
