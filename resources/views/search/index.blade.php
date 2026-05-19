@extends('layouts.public')
@section('title', 'Search')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Search</h1>
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-8 grid grid-cols-1 sm:grid-cols-5 gap-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Keyword" class="rounded border-gray-300 w-full">
        <select name="type" class="rounded border-gray-300 w-full">
            <option value="products" @selected($type === 'products')>Products</option>
            <option value="businesses" @selected($type === 'businesses')>Businesses</option>
            <option value="all" @selected($type === 'all')>All</option>
        </select>
        <select name="category" class="rounded border-gray-300 w-full">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="city" value="{{ request('city') }}" placeholder="City" class="rounded border-gray-300 w-full">
        <button class="bg-indigo-600 text-white rounded px-4 py-2">Search</button>
    </form>

    @if ($type === 'businesses' || $type === 'all')
        <h2 class="text-xl font-bold mb-4">Businesses</h2>
        @if ($businesses instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                @forelse ($businesses as $business)
                    <a href="{{ route('businesses.show', $business) }}" class="bg-white p-4 rounded shadow">{{ $business->name }} — {{ $business->city }}</a>
                @empty
                    <p class="text-gray-500">No businesses found.</p>
                @endforelse
            </div>
            {{ $businesses->withQueryString()->links('pagination::tailwind', ['pageName' => 'business_page']) }}
        @endif
    @endif

    @if ($type === 'products' || $type === 'all')
        <h2 class="text-xl font-bold mb-4 mt-8">Products</h2>
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">
                @forelse ($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="bg-white p-4 rounded shadow">{{ $product->name }} — ${{ number_format($product->price, 2) }}</a>
                @empty
                    <p class="text-gray-500">No products found.</p>
                @endforelse
            </div>
            {{ $products->withQueryString()->links('pagination::tailwind', ['pageName' => 'product_page']) }}
        @endif
    @endif
</div>
@endsection
