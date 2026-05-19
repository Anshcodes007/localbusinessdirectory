<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';

    protected $collection = 'users';

    public const ROLE_ADMIN = 'admin';

    public const ROLE_BUSINESS_OWNER = 'business_owner';

    public const ROLE_USER = 'user';

    /** @deprecated Use ROLE_USER */
    public const ROLE_CUSTOMER = 'user';

    protected $fillable = [
        'name',
        'username',
        'email',
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

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isBusinessOwner(): bool
    {
        return $this->role === self::ROLE_BUSINESS_OWNER;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isCustomer(): bool
    {
        return $this->isUser();
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
