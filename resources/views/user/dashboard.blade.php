@extends('layouts.public')
@section('title', 'User Dashboard')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-green-600 text-white rounded-xl p-6 mb-8">
        <h1 class="text-2xl font-bold">Welcome, User {{ auth()->user()->name }}!</h1>
        <p class="text-green-100 mt-1">Browse businesses, order products, and track your orders.</p>
    </div>
    <x-alert />
    <div class="grid grid-cols-2 gap-4 mb-8 max-w-md">
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['orders'] }}</p>
            <p class="text-xs text-gray-500">Total Orders</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500">Pending</p>
        </div>
    </div>
    <div class="flex gap-3 mb-8 flex-wrap">
        <a href="{{ route('businesses.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Browse Businesses</a>
        <a href="{{ route('orders.index') }}" class="bg-white border px-4 py-2 rounded shadow">My Order Summary</a>
    </div>
    <h2 class="text-lg font-bold mb-4">Recent Orders</h2>
    @forelse ($recentOrders as $order)
        <div class="bg-white p-4 rounded shadow mb-2 text-sm">
            <strong>{{ $order->business_name }}</strong> — ${{ number_format($order->total_price, 2) }} — {{ $order->statusLabel() }}
            <span class="text-gray-400">({{ $order->created_at->format('M d, Y H:i') }})</span>
        </div>
    @empty
        <p class="text-gray-500">No orders yet.</p>
    @endforelse
</div>
@endsection
