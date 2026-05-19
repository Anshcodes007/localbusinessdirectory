<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $businessIds = $request->user()->businesses()->pluck('id')->map(fn ($id) => (string) $id);

        $stats = [
            'products' => Product::whereIn('business_id', $businessIds)->count(),
            'orders' => Order::whereIn('business_id', $businessIds)->count(),
            'pending_orders' => Order::whereIn('business_id', $businessIds)
                ->where('status', Order::STATUS_PENDING)
                ->count(),
        ];

        $recentOrders = Order::whereIn('business_id', $businessIds)
            ->latest()
            ->take(5)
            ->get();

        $products = Product::whereIn('business_id', $businessIds)
            ->with('business')
            ->latest()
            ->take(8)
            ->get();

        return view('owner.dashboard', compact('stats', 'recentOrders', 'products'));
    }
}
