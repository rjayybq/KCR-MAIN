<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
     use HasFactory;

   protected $fillable = [
        'product_id',
        'cashier_id',
        'customer_name',
        'customer_email',
        'quantity',
        'total_price',
        
    ];

    

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
