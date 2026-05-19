@extends('layouts.public')
@section('title', $product->name)
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <x-alert />
    <div class="bg-white rounded-lg shadow p-6 md:flex gap-6">
        @if ($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full md:w-80 h-64 object-cover rounded-lg">
        @endif
        <div class="flex-1">
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-2xl text-indigo-600 font-bold mb-4">${{ number_format($product->price, 2) }}</p>
            <p class="text-gray-600 mb-4">{{ $product->description }}</p>
            <p class="text-sm mb-1"><strong>Stock:</strong> {{ str_replace('_', ' ', ucfirst($product->stock_status)) }}</p>
            <p class="text-sm mb-1"><strong>Quantity:</strong> {{ $product->quantity }}</p>
            @if ($product->business)
                <a href="{{ route('businesses.show', $product->business) }}" class="text-indigo-600 hover:underline text-sm">View business: {{ $product->business->name }}</a>
            @endif
            @auth
                @if (auth()->user()->isCustomer() && $product->isInStock())
                    <form action="{{ route('orders.store', $product) }}" method="POST" class="mt-4 flex gap-2 items-end">
                        @csrf
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Quantity</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="rounded border-gray-300 w-24" required>
                        </div>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Place Order</button>
                    </form>
                @elseif (auth()->user()->isCustomer())
                    <p class="mt-4 text-red-600 text-sm">Out of stock</p>
                @endif
                @if (auth()->user()->isAdmin() || (string) $product->business?->user_id === (string) auth()->id())
                    <div class="mt-4">
                        <a href="{{ route('products.edit', $product) }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Edit Product</a>
                    </div>
                @endif
            @else
                <p class="mt-4 text-sm text-gray-500"><a href="{{ route('login') }}" class="text-indigo-600">Log in</a> to order.</p>
            @endauth
        </div>
    </div>
</div>
@endsection
