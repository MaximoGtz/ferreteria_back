<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id', 'total'
    ];
    public function client()
    {
        return $this->belongsToOne(Client::class);
    }
    public function productCart()
    {
        return $this->hasMany(ProductsCart::class);
    }
    public function sell()
    {
        return $this->hasOne(Sell::class);
    }
}
