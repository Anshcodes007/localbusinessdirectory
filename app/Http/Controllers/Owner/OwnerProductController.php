<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class OwnerProductController extends Controller
{
    public function index(Request $request)
    {
        $businessIds = $request->user()->businesses()->pluck('id')->map(fn ($id) => (string) $id);

        $products = Product::whereIn('business_id', $businessIds)
            ->with('business')
            ->latest()
            ->paginate(15);

        return view('owner.products.index', compact('products'));
    }
}
