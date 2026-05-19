<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'products';

    public const STOCK_IN = 'in_stock';

    public const STOCK_LOW = 'low_stock';

    public const STOCK_OUT = 'out_of_stock';

    protected $fillable = [
        'business_id',
        'category_id',
        'name',
        'description',
        'price',
        'discount',
        'gst',
        'image',
        'stock_status',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'discount' => 'float',
            'gst' => 'float',
            'quantity' => 'integer',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function isInStock(): bool
    {
        return $this->quantity > 0 && $this->stock_status !== self::STOCK_OUT;
    }
}
