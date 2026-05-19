<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $type = $request->get('type', 'products');

        $businesses = collect();
        $products = collect();

        if ($type === 'businesses' || $type === 'all') {
            $businessQuery = Business::where('is_active', true)->with('category');

            if ($request->filled('q')) {
                $businessQuery->where('name', 'like', '%'.$request->q.'%');
            }
            if ($request->filled('category')) {
                $businessQuery->where('category_id', $request->category);
            }
            if ($request->filled('city')) {
                $businessQuery->where('city', 'like', '%'.$request->city.'%');
            }

            $businesses = $businessQuery->latest()->paginate(12, ['*'], 'business_page');
        }

        if ($type === 'products' || $type === 'all') {
            $productQuery = Product::with(['business', 'category']);

            if ($request->filled('q')) {
                $productQuery->where('name', 'like', '%'.$request->q.'%');
            }
            if ($request->filled('category')) {
                $productQuery->where('category_id', $request->category);
            }
            if ($request->filled('city')) {
                $productQuery->whereHas('business', function ($q) use ($request) {
                    $q->where('city', 'like', '%'.$request->city.'%');
                });
            }

            $products = $productQuery->latest()->paginate(12, ['*'], 'product_page');
        }

        return view('search.index', compact('categories', 'businesses', 'products', 'type'));
    }
}
