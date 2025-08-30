<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'ProductName',
        'category_id',
        'weight',
        'unit',
        'stock',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
