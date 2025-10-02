<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [ 'date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function ingredients()
    // {
    //     return $this->belongsToMany(Ingredient::class, 'ingredient_stock')
    //         ->withPivot( 'date')
    //         ->withTimestamps();
    // }


public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_stock')
                     ->withPivot('type', 'movement_qty', 'date')
                     ->withTimestamps();
    }
}
