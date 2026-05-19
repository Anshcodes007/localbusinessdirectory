@extends('layouts.public')
@section('title', 'Products')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <x-alert />
    <h1 class="text-3xl font-bold mb-6">Product Listings</h1>
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-1 sm:grid-cols-5 gap-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="rounded border-gray-300 w-full">
        <select name="category" class="rounded border-gray-300 w-full">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="city" value="{{ request('city') }}" placeholder="City" class="rounded border-gray-300 w-full">
        <select name="stock_status" class="rounded border-gray-300 w-full">
            <option value="">All stock</option>
            <option value="in_stock" @selected(request('stock_status') === 'in_stock')>In stock</option>
            <option value="low_stock" @selected(request('stock_status') === 'low_stock')>Low stock</option>
            <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Out of stock</option>
        </select>
        <button class="bg-indigo-600 text-white rounded px-4 py-2">Filter</button>
    </form>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <a href="{{ route('products.show', $product) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md">
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-36 object-cover">
                @else
                    <span class="block w-full h-36 bg-gray-200 text-center leading-[9rem] text-gray-400 text-sm">No image</span>
                @endif
                <span class="block p-4">
                    <span class="block font-semibold">{{ $product->name }}</span>
                    <span class="block text-indigo-600 font-bold">${{ number_format($product->price, 2) }}</span>
                    <span class="block text-xs text-gray-500">{{ $product->business->name ?? '' }}</span>
                </span>
            </a>
        @empty
            <p class="text-gray-500 col-span-full">No products found.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
