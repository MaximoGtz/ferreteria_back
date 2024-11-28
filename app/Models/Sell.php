<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sell extends Model
{
    protected $fillable = [
        'cart_id', 'client_id', 'total', 'iva', 'purchase_method'
    ];
    public function cart()
{
    return $this->belongsTo(Cart::class);
}

public function client()
{
    return $this->belongsTo(User::class, 'client_id');
}
public function product_cart()
{
    return $this->hasMany(product_cart::class, 'client_id');
}


}
