<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Order;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'orders' => Order::where('user_id', (string) $user->id)->count(),
            'pending' => Order::where('user_id', (string) $user->id)->where('status', Order::STATUS_PENDING)->count(),
        ];

        $recentOrders = Order::where('user_id', (string) $user->id)->latest()->take(5)->get();
        $businesses = Business::where('is_active', true)->latest()->take(6)->get();

        return view('user.dashboard', compact('stats', 'recentOrders', 'businesses'));
    }
}
