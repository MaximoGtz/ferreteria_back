<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductsCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'subtotal', 'state'
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function producto()
{
    return $this->belongsTo(Product::class, 'product_id'); // Ajusta el campo si es necesario
}
}
