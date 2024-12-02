<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        "product_id",
        "user_id",
        "rate",
        "comment",

    ];
    public function products()
{
    return $this->belongsTo(Product::class);
    
}
public function user()
{
    return $this->belongsTo(User::class);
}
}
