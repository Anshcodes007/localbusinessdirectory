<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Food & Dining',
            'Retail & Shopping',
            'Health & Wellness',
            'Services',
            'Technology',
            'Home & Garden',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Businesses and products in {$name}.",
                ]
            );
        }
    }
}
