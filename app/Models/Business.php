<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Business extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'businesses';

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'city',
        'state',
        'address',
        'phone',
        'email',
        'logo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'business_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'business_id');
    }
}
