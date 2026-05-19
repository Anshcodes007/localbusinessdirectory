<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::orderBy('name')->get();
            $featuredBusinesses = Business::where('is_active', true)->latest()->take(6)->get();
            $latestProducts = Product::with('business')->latest()->take(8)->get();
            $dbError = null;
        } catch (Throwable $e) {
            $categories = collect();
            $featuredBusinesses = collect();
            $latestProducts = collect();
            $dbError = 'Database is not connected. Start MongoDB, then run: php artisan db:seed';
        }

        return view('home.index', compact('categories', 'featuredBusinesses', 'latestProducts', 'dbError'));
    }
}
