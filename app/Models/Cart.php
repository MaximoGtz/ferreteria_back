<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function client()
    {
        return $this->belongsToOne(Client::class);
    }
    public function productCart()
    {
        return $this->hasMany(ProductCart::class);
    }
    public function sell()
    {
        return $this->hasOne(Sell::class);
    }
}
