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
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
