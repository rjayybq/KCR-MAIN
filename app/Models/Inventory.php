<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ProductName',
        'category_id',
        'weight',
        'unit',
        'stock',
        'price',
    ];

    /**
     * Each inventory item belongs to one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
