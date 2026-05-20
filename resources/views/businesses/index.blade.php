@extends(auth()->check() && auth()->user()->isUser() ? 'layouts.app-dashboard' : 'layouts.public')

@section('title', 'Businesses - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-alert />

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Businesses</h1>
        <p class="text-slate-500 mt-1.5 text-sm sm:text-base">Discover and connect with great local businesses near you.</p>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('businesses.index') }}" class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
        <div>
            <label for="search-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Search Name</label>
            <input type="text" id="search-input" name="q" value="{{ request('q') }}" placeholder="Search business name..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search by business name">
        </div>
        <div>
            <label for="city-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
            <input type="text" id="city-input" name="city" value="{{ request('city') }}" placeholder="Enter city..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search by city">
        </div>
        <div>
            <label for="state-input" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">State</label>
            <input type="text" id="state-input" name="state" value="{{ request('state') }}" placeholder="Enter state..." class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" aria-label="Search by state">
        </div>
        <div>
            <label for="category-select" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Category</label>
            <select id="category-select" name="category" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-600" aria-label="Filter by category">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 text-sm h-[42px]" aria-label="Apply filters">
                Apply Filters
            </button>
        </div>
    </form>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($businesses as $business)
            <x-business-card :business="$business" />
        @empty
            <div class="col-span-full bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">No businesses found</h3>
                <p class="text-slate-400 mt-1.5 text-sm max-w-md mx-auto">We couldn't find any businesses matching your search queries. Try modifying your filters or keywords.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $businesses->withQueryString()->links() }}
    </div>
</div>
@endsection
