@extends('layouts.app-dashboard')

@section('title', 'User Dashboard - ' . config('app.name'))

@php
    $user = auth()->user();
    
    // Fetch categories dynamically for quick navigation
    $categories = \App\Models\Category::orderBy('name')->take(5)->get();

    // Map category names to SVG icons
    $categoryIcons = [
        'food & dining' => '<svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
        'retail & shopping' => '<svg class="w-6 h-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>',
        'health & wellness' => '<svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>',
        'services' => '<svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
        'technology' => '<svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
    ];
@endphp

@section('content')
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
            Welcome back, <span class="text-indigo-600">{{ $user->name }}!</span> 👋
        </h1>
        <p class="text-slate-500 mt-1.5 text-sm sm:text-base">Browse businesses, order products, and track your orders.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Total Orders -->
        <x-stat-card 
            :count="$stats['orders']" 
            label="Total Orders" 
            color="green" 
            link="{{ route('orders.index') }}" 
            linkText="View all orders →">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </x-stat-card>

        <!-- Pending Orders -->
        <x-stat-card 
            :count="$stats['pending']" 
            label="Pending Orders" 
            color="yellow" 
            link="{{ route('orders.index', ['status' => 'pending']) }}" 
            linkText="View pending orders →">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-stat-card>

        <!-- Gradient Promo Card -->
        <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white rounded-2xl p-6 shadow-sm flex flex-col justify-between relative overflow-hidden group h-full">
            <!-- Background Decor -->
            <div class="absolute right-0 bottom-0 opacity-10 translate-y-12 translate-x-12 group-hover:scale-110 transition duration-500">
                <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/>
                </svg>
            </div>

            <div>
                <h3 class="font-extrabold text-xl tracking-tight leading-snug">Discover great businesses near you</h3>
                <p class="text-indigo-100 text-xs sm:text-sm mt-2 font-medium opacity-90 max-w-[90%]">Find products and services from trusted local businesses.</p>
            </div>
            <div class="mt-8 pt-4">
                <a href="{{ route('businesses.index') }}" class="inline-flex items-center justify-center bg-white text-indigo-700 font-bold px-5 py-2.5 rounded-xl hover:bg-indigo-50 text-xs sm:text-sm shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-white" aria-label="Browse all businesses">
                    Browse Businesses
                </a>
            </div>
        </div>
    </div>

    <!-- Layout Rows for Recent Orders & Top Categories -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-slate-800">Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline" aria-label="View all orders list">View all orders</a>
                </div>
                
                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-50 text-slate-400 font-semibold text-xs tracking-wider">
                                <th class="pb-3">Business</th>
                                <th class="pb-3">Items</th>
                                <th class="pb-3 text-right">Total Price</th>
                                <th class="pb-3 pl-4">Status</th>
                                <th class="pb-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($recentOrders as $order)
                                <tr class="text-slate-700 group">
                                    <td class="py-3.5 font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                        {{ $order->business_name }}
                                    </td>
                                    <td class="py-3.5 text-slate-500 max-w-[150px] truncate">
                                        @php
                                            $itemSummary = collect($order->items)->map(fn($item) => $item['product_name'] . ' x' . $item['quantity'])->join(', ');
                                        @endphp
                                        {{ $itemSummary }}
                                    </td>
                                    <td class="py-3.5 text-right font-extrabold text-slate-900">
                                        ${{ number_format($order->total_price, 2) }}
                                    </td>
                                    <td class="py-3.5 pl-4">
                                        <x-status-badge :status="$order->status" />
                                    </td>
                                    <td class="py-3.5 text-right text-slate-400 text-xs">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                        No orders placed yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Explore Top Categories -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-slate-800">Explore Top Categories</h2>
                <a href="{{ route('businesses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline" aria-label="View all business categories">View all</a>
            </div>

            <div class="space-y-3">
                @forelse ($categories as $category)
                    @php
                        $catKey = strtolower($category->name);
                        $icon = $categoryIcons[$catKey] ?? '<svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>';
                    @endphp
                    <a href="{{ route('businesses.index', ['category' => $category->id]) }}" class="flex items-center gap-4 p-3 rounded-xl border border-slate-50 hover:border-indigo-100 hover:bg-indigo-50/20 transition group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500" aria-label="View businesses under category {{ $category->name }}">
                        <div class="p-2 bg-slate-50 rounded-xl group-hover:bg-white transition duration-300">
                            {!! $icon !!}
                        </div>
                        <div>
                            <span class="block font-bold text-sm text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</span>
                            <span class="block text-slate-400 text-xs mt-0.5">{{ $category->description ?? 'Browse items' }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-slate-400 py-8 text-sm">No categories found.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
