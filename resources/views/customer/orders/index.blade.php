@extends('layouts.public')
@section('title', 'My Orders')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    <x-alert />
    <div class="space-y-4">
        @forelse ($orders as $order)
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex flex-wrap justify-between gap-2 mb-3">
                    <div>
                        <p class="font-semibold">{{ $order->business_name }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $order->statusLabel() }}
                    </span>
                </div>
                <ul class="text-sm text-gray-600 mb-3">
                    @foreach ($order->items as $item)
                        <li>{{ $item['product_name'] }} x{{ $item['quantity'] }} — ${{ number_format($item['price'] * $item['quantity'], 2) }}</li>
                    @endforeach
                </ul>
                <p class="font-bold text-indigo-600">Total: ${{ number_format($order->total_price, 2) }}</p>
                @if ($order->isCancellable())
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-3" onsubmit="return confirm('Cancel this order? Stock will be restored.')">
                        @csrf @method('PATCH')
                        <button class="text-red-600 text-sm hover:underline">Cancel Order</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500 bg-white p-6 rounded-lg shadow text-center">You have not placed any orders yet.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
</div>
@endsection
