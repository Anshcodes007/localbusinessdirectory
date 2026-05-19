@extends('layouts.public')

@section('title', 'Home - '.config('app.name'))

@section('content')
<div class="bg-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-4 py-16 sm:py-24 text-center">
        <h1 class="text-3xl sm:text-5xl font-bold mb-4">Find Local Businesses & Products</h1>
        <p class="text-indigo-100 mb-8 max-w-2xl mx-auto">Discover shops, services, and products in your city.</p>
        <form action="{{ route('businesses.by-city') }}" method="GET" class="max-w-2xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-2 mb-3">
            <input type="text" name="city" placeholder="City" class="rounded-lg px-4 py-3 text-gray-900" value="{{ request('city') }}">
            <input type="text" name="state" placeholder="State" class="rounded-lg px-4 py-3 text-gray-900" value="{{ request('state') }}">
            <button type="submit" class="bg-white text-indigo-700 font-semibold px-6 py-3 rounded-lg hover:bg-indigo-50">Search by Location</button>
        </form>
        <form action="{{ route('search.index') }}" method="GET" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-2">
            <input type="text" name="q" placeholder="Search products or businesses..."
                class="flex-1 rounded-lg px-4 py-3 text-gray-900" value="{{ request('q') }}">
            <button type="submit" class="bg-white/90 text-indigo-700 font-semibold px-6 py-3 rounded-lg hover:bg-indigo-50">Search All</button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    @if (!empty($dbError))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">{{ $dbError }}</div>
    @endif
    <x-alert />

    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Browse by Category</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('businesses.index', ['category' => $category->id]) }}"
                   class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition">
                    <span class="font-medium text-sm">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Featured Businesses</h2>
            <a href="{{ route('businesses.index') }}" class="text-indigo-600 hover:underline text-sm">View all</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($featuredBusinesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition">
                    @if ($business->logo)
                        <img src="{{ asset('storage/'.$business->logo) }}" alt="{{ $business->name }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">No logo</div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold">{{ $business->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $business->city }}</p>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 col-span-full">No businesses yet.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Latest Products</h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline text-sm">View all</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($latestProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition">
                    @if ($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-36 object-cover">
                    @else
                        <div class="w-full h-36 bg-gray-200 flex items-center justify-center text-gray-400 text-sm">No image</div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-sm">{{ $product->name }}</h3>
                        <p class="text-indigo-600 font-bold">${{ number_format($product->price, 2) }}</p>
                        @if ($product->business)
                            <p class="text-xs text-gray-500">{{ $product->business->name }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-gray-500 col-span-full">No products yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
