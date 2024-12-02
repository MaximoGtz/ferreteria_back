<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id', 'name', 'brand_id', 'sell_price', 'buy_price',
        'bar_code', 'stock', 'description', 'state', 'wholesale_price'
    ];

    // Relationship with the Image model
    public function images()
    {
        return $this->belongsToMany(Image::class, 'products_images', 'product_id', 'image_id');
    }
    public function category()
{
    return $this->belongsTo(Category::class);
}
    public function brand()
{
    return $this->belongsTo(Brand::class);
}
}

