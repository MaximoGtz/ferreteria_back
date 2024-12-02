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
    return $this->belongsTo(User::class); // Ajusta el nombre de la clave foránea si es necesario
}
public function producto_cart()
{
    return $this->hasMany(ProductsCart::class); // Ajusta el nombre de la clave foránea si es necesario
}
    public function sell()
    {
        return $this->hasOne(Sell::class);
    }
}
