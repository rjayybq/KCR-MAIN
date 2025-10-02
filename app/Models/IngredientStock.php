<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientStock extends Model
{
     protected $table = 'ingredient_stock';
     protected $fillable = ['ingredient_id', 'type', 'movement_qty', 'date'];


     public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

}
