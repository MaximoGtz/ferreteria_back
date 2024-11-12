<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    public function cart()
    {
        return $this->belongsToOne(Cart::class);
    }
    public function product()
    {
        return $this->belongsToMany(Product::class);
    }
}
