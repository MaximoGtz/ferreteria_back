<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Direction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'state',
        'city',
        'postal_code',
        'name',
        'residence',
        'description',
        'residence',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sell()
{
    return $this->hasOne(Sell::class, 'client_id');
}
}
