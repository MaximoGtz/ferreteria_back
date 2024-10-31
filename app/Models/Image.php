<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = ['image'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_images', 'image_id', 'product_id');
    }
    
}
