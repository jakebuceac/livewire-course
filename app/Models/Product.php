<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'colour',
        'in_stock',
    ];

    const COLOR_LIST = [
        'red' => 'Red',
        'green' => 'Green',
        'blue' => 'Blue',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
