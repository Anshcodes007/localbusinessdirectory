@extends('layouts.public')
@section('title', $business->name)
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <x-alert />
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="md:flex">
            @if ($business->logo)
                <img src="{{ asset('storage/'.$business->logo) }}" alt="{{ $business->name }}" class="w-full md:w-64 h-48 md:h-auto object-cover">
            @endif
            <div class="p-6 flex-1">
                <h1 class="text-3xl font-bold mb-2">{{ $business->name }}</h1>
                @if ($business->category)
                    <p class="text-indigo-600 text-sm mb-2">{{ $business->category->name }}</p>
                @endif
                <p class="text-gray-600 mb-4">{{ $business->description }}</p>
                <p class="text-sm"><strong>City:</strong> {{ $business->city }}@if($business->state), {{ $business->state }}@endif</p>
                <p class="text-sm"><strong>Address:</strong> {{ $business->address }}</p>
                <p class="text-sm"><strong>Phone:</strong> {{ $business->phone }}</p>
                <p class="text-sm"><strong>Email:</strong> {{ $business->email }}</p>
                @auth
                    @if (auth()->user()->isAdmin() || (string) $business->user_id === (string) auth()->id())
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <a href="{{ route('businesses.edit', $business) }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Edit</a>
                            <a href="{{ route('products.create', $business) }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm">Add Product</a>
                            <form action="{{ route('businesses.destroy', $business) }}" method="POST" onsubmit="return confirm('Delete this business?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-4">Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
        @forelse ($business->products as $product)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-semibold">{{ $product->name }}</h3>
                    <span class="text-indigo-600 font-bold">${{ number_format($product->price, 2) }}</span>
                </div>
                <p class="text-sm text-gray-500 mb-2">Stock: {{ $product->quantity }}</p>
                <span class="text-xs px-2 py-1 rounded {{ $product->stock_status === 'in_stock' ? 'bg-green-100 text-green-800' : ($product->stock_status === 'low_stock' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ str_replace('_', ' ', ucfirst($product->stock_status)) }}
                </span>
                @auth
                    @if (auth()->user()->isCustomer() && $product->isInStock())
                        <form action="{{ route('orders.store', $product) }}" method="POST" class="mt-4 flex gap-2 items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="block text-xs text-gray-500 mb-1">Qty</label>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="w-full rounded border-gray-300 text-sm" required>
                            </div>
                            <button type="submit" class="bg-indigo-600 text-white px-3 py-2 rounded text-sm hover:bg-indigo-700">Order</button>
                        </form>
                    @elseif (auth()->user()->isCustomer())
                        <p class="mt-3 text-xs text-red-600">Out of stock</p>
                    @endif
                @else
                    <p class="mt-3 text-xs text-gray-500"><a href="{{ route('login') }}" class="text-indigo-600">Log in</a> to place an order.</p>
                @endauth
                <a href="{{ route('products.show', $product) }}" class="text-xs text-indigo-600 mt-2 inline-block">View details</a>
            </div>
        @empty
            <p class="text-gray-500 col-span-full">No products listed yet.</p>
        @endforelse
    </div>

    <h2 class="text-2xl font-bold mb-4">Reviews</h2>
    @auth
        @if (auth()->user()->isCustomer())
        <form action="{{ route('reviews.store', $business) }}" method="POST" class="bg-white p-4 rounded-lg shadow mb-6">
            @csrf
            <label class="block text-sm font-medium mb-1">Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" value="{{ old('rating', 5) }}" class="rounded border-gray-300 mb-3 w-24" required>
            <label class="block text-sm font-medium mb-1">Comment</label>
            <textarea name="comment" rows="3" class="w-full rounded border-gray-300 mb-3" required>{{ old('comment') }}</textarea>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded">Submit Review</button>
        </form>
        @endif
    @endauth
    <div class="space-y-4">
        @forelse ($business->reviews as $review)
            <div class="bg-white p-4 rounded-lg shadow">
                <p class="font-semibold">{{ $review->user->name ?? 'User' }} — {{ $review->rating }}/5</p>
                <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
            </div>
        @empty
            <p class="text-gray-500">No reviews yet.</p>
        @endforelse
    </div>
</div>
@endsection
