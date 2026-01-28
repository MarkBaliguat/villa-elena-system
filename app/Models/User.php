<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'userID';
    public $incrementing = true;
    

    protected $fillable = [
        'name',
        'username',
        'email',
        'phoneNumber',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     public function getRouteKeyName()
    {
        return 'userID';
    }
    
    // Relationships
    public function carts()
    {
        return $this->hasMany(Cart::class, 'userID');
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Cart::class, 'userID', 'cartID');
    }

    public function isGuest()
    {
        return $this->role === 'guest';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }
}