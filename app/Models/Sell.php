<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    public function cart()
{
    return $this->belongsTo(Cart::class);
}
public function client()
{
    return $this->belongsTo(Client::class);
}
}
