<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'comment',
        'email', // Añadir email al fillable
    ];

    // Especificar el nombre de la tabla si no sigue la convención
    protected $table = 'normalcomments';
}
