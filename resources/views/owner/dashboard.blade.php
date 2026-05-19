@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-amber-500 text-white rounded-xl p-6 mb-8">
        <h1 class="text-2xl font-bold">Welcome, Business Owner {{ auth()->user()->name }}!</h1>
        <p class="text-amber-100 mt-1">Manage your products, stock, and customer orders.</p>
    </div>
    <x-alert />
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['products'] }}</p>
            <p class="text-sm text-gray-500">Products</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['orders'] }}</p>
            <p class="text-sm text-gray-500">Total Orders</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
            <p class="text-sm text-gray-500">Pending Orders</p>
        </div>
    </div>
    <div class="flex gap-3 mb-8 flex-wrap">
        @php $business = auth()->user()->businesses->first(); @endphp
        @if ($business)
            <a href="{{ route('products.create', $business) }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">+ Add Product</a>
        @endif
        <a href="{{ route('owner.products.index') }}" class="bg-white border px-4 py-2 rounded shadow text-sm">View Products</a>
        <a href="{{ route('owner.orders.index') }}" class="bg-white border px-4 py-2 rounded shadow text-sm">View Orders</a>
    </div>
    <h2 class="text-lg font-bold mb-4">Recent Customer Orders</h2>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Product</th>
                    <th class="px-4 py-3 text-left">Qty</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Date & Time</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $order)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $order->user_name }}</td>
                        <td class="px-4 py-3">{{ $order->items[0]['product_name'] ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->items[0]['quantity'] ?? '-' }}</td>
                        <td class="px-4 py-3">${{ number_format($order->total_price, 2) }}</td>
                        <td class="px-4 py-3">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">{{ $order->statusLabel() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
