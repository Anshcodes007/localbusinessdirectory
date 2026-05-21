<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'business_owners' => User::where('role', User::ROLE_BUSINESS_OWNER)->count(),
            'users' => User::where('role', User::ROLE_USER)->count(),
            'businesses' => Business::count(),
            'products' => Product::count(),
            'categories' => \App\Models\Category::count(),
            'orders' => Order::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
