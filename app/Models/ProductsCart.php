<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductsCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'subtotal'
    ];
    public function cart()
    {
        return $this->belongsToMany(Cart::class);
    }
    public function product()
    {
        return $this->belongsToMany(Product::class);
    }
}
