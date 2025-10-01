<?php

namespace App\Models;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
  protected $fillable = ['name', 'stock', 'unit'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'ingredient_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

   public function stocks()
  {
      return $this->belongsToMany(Stock::class, 'ingredient_stock')
                  ->withTimestamps();
  }

}
