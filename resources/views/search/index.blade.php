@extends(auth()->check() && auth()->user()->isUser() ? 'layouts.app-dashboard' : 'layouts.public')

@section('title', 'Search - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Search</h1>
        <p class="text-slate-500 mt-1.5 text-sm sm:text-base">Find businesses or products easily from your local community.</p>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('search.index') }}" class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm mb-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <div>
            <label for="search-q" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Keyword</label>
            <input type="text" id="search-q" name="q" value="{{ request('q') }}" placeholder="Search query..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search keyword">
        </div>
        <div>
            <label for="search-type" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Search Type</label>
            <select id="search-type" name="type" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-600" aria-label="Search item type">
                <option value="all" @selected($type === 'all')>All Results</option>
                <option value="businesses" @selected($type === 'businesses')>Businesses Only</option>
                <option value="products" @selected($type === 'products')>Products Only</option>
            </select>
        </div>
        <div>
            <label for="search-category" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Category</label>
            <select id="search-category" name="category" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-600" aria-label="Filter by category">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="search-city" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
            <input type="text" id="search-city" name="city" value="{{ request('city') }}" placeholder="Enter city..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search by city">
        </div>
        <div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 text-sm h-[42px]" aria-label="Perform search">
                Search
            </button>
        </div>
    </form>

    <!-- Search Results Header -->
    @if(request('q') || request('city') || request('category'))
        <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-sm font-semibold text-slate-400">
                Search Results for <span class="text-slate-800 font-bold">"{{ request('q') ?: (request('city') ?: 'All Locations') }}"</span>
            </span>
        </div>
    @endif

    <!-- Businesses Results Section -->
    @if ($type === 'businesses' || $type === 'all')
        @if ($businesses instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mb-10">
                <h2 class="text-xl font-black text-slate-800 tracking-tight mb-5 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    Matching Businesses
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($businesses as $business)
                        <x-business-card :business="$business" />
                    @empty
                        <p class="text-slate-400 bg-white border border-slate-50 p-6 rounded-2xl shadow-sm col-span-full font-medium">No businesses found matching your criteria.</p>
                    @endforelse
                </div>
                
                <div class="mt-6">
                    {{ $businesses->withQueryString()->links('pagination::tailwind', ['pageName' => 'business_page']) }}
                </div>
            </div>
        @endif
    @endif

    <!-- Products Results Section -->
    @if ($type === 'products' || $type === 'all')
        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mb-10">
                <h2 class="text-xl font-black text-slate-800 tracking-tight mb-5 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    Matching Products
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse ($products as $product)
                        <x-product-card :product="$product" />
                    @empty
                        <p class="text-slate-400 bg-white border border-slate-50 p-6 rounded-2xl shadow-sm col-span-full font-medium">No products found matching your criteria.</p>
                    @endforelse
                </div>
                
                <div class="mt-6">
                    {{ $products->withQueryString()->links('pagination::tailwind', ['pageName' => 'product_page']) }}
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
