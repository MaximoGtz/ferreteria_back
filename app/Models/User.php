<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'image', 
        'email',
        'phone',
        'rfc',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Definir la relación con el modelo Directions.
     * Asumiendo que un usuario puede tener múltiples direcciones.
     */
    public function directions()
    {
        return $this->hasMany(Direction::class); 
    }
    public function cart()
{
    return $this->hasOne(Cart::class);
}
public function sells()
{
    return $this->hasMany(Sell::class);
}
public function comments()
{
    return $this->hasMany(Comment::class);
}
}
