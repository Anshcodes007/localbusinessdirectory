<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'reviews';

    protected $fillable = [
        'user_id',
        'business_id',
        'product_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'verified_purchase',
    ];

    protected function casts(): array
    {
        return [
            'rating'            => 'integer',
            'verified_purchase' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** Scope: reviews for a specific business (not product) */
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', (string) $businessId)->whereNull('product_id');
    }

    /** Scope: reviews for a specific product */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', (string) $productId);
    }

    /** Returns "Positive" / "Neutral" / "Negative" sentiment label */
    public function sentimentLabel(): string
    {
        if ($this->rating >= 4) return 'Positive';
        if ($this->rating === 3) return 'Neutral';
        return 'Negative';
    }

    /** CSS classes for the sentiment badge */
    public function sentimentClass(): string
    {
        if ($this->rating >= 4) return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        if ($this->rating === 3) return 'bg-amber-50 text-amber-700 border-amber-200';
        return 'bg-rose-50 text-rose-700 border-rose-200';
    }
}
