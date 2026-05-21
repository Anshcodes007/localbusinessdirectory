@extends(auth()->check() && auth()->user()->isUser() ? 'layouts.app-dashboard' : 'layouts.public')

@section('title', 'Products - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-alert />

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Products</h1>
        <p class="text-slate-500 mt-1.5 text-sm sm:text-base">Browse and order high-quality products from trusted local shops.</p>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('products.index') }}" class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <div>
            <label for="prod-search" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Search</label>
            <input type="text" id="prod-search" name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search by product name">
        </div>
        <!-- <div>
            <label for="prod-category" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Category</label>
            <select id="prod-category" name="category" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-600" aria-label="Filter by category">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div> -->
        <div>
            <label for="prod-city" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
            <input type="text" id="prod-city" name="city" value="{{ request('city') }}" placeholder="Enter city..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search by city">
        </div>
        <div>
            <label for="prod-stock" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Stock status</label>
            <select id="prod-stock" name="stock_status" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-600" aria-label="Filter by stock status">
                <option value="">All stock</option>
                <option value="in_stock" @selected(request('stock_status') === 'in_stock')>In stock</option>
                <option value="low_stock" @selected(request('stock_status') === 'low_stock')>Low stock</option>
                <option value="out_of_stock" @selected(request('stock_status') === 'out_of_stock')>Out of stock</option>
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 text-sm h-[42px]" aria-label="Apply filters">
                Apply Filters
            </button>
        </div>
    </form>
<!-- 
    Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-full bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">No products found</h3>
                <p class="text-slate-400 mt-1.5 text-sm max-w-md mx-auto">We couldn't find any products matching your search criteria. Try adjusting your search text or selected category.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
