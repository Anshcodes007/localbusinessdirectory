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
        $businessIds = $request->user()->businesses()->pluck('id')->map(fn ($id) => (string) $id)->toArray();

        $allOrders = Order::whereIn('business_id', $businessIds)->get();

        $totalRevenue = $allOrders->where('status', '!=', Order::STATUS_CANCELLED)->sum('total_price');

        $stats = [
            'products'       => Product::whereIn('business_id', $businessIds)->count(),
            'orders'         => $allOrders->count(),
            'pending_orders' => $allOrders->where('status', Order::STATUS_PENDING)->count(),
            'revenue'        => $totalRevenue,
        ];

        $recentOrders = Order::whereIn('business_id', $businessIds)
            ->latest()
            ->take(5)
            ->get();

        // 7-day daily revenue for the mini chart
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayRevenue = Order::whereIn('business_id', $businessIds)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $dailyRevenue[] = ['date' => now()->subDays($i)->format('M d'), 'revenue' => round($dayRevenue, 2)];
        }

        $business = $request->user()->businesses()->first();

        return view('owner.dashboard', compact('stats', 'recentOrders', 'dailyRevenue', 'business'));
    }
}
