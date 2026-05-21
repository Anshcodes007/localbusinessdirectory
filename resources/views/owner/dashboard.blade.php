@extends('layouts.owner-dashboard')

@section('title', 'Owner Dashboard - ' . config('app.name'))

@section('content')
@php
    $user = auth()->user();
    $words = explode(' ', $user->name);
    $initials = '';
    foreach ($words as $word) { $initials .= strtoupper(substr($word, 0, 1)); }
    $initials = substr($initials, 0, 2) ?: 'BO';
@endphp

<!-- Welcome Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <p class="text-sm text-slate-500 font-medium">Welcome back,</p>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
            {{ $user->name }} 👋
        </h1>
        <p class="text-slate-400 text-sm mt-1">Here's what's happening with your business today.</p>
    </div>
    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm self-start sm:self-auto">
        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        {{ now()->subDays(6)->format('M d') }} – {{ now()->format('M d, Y') }}
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Total Products -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Products</p>
            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['products'] }}</p>
        <a href="{{ route('owner.products.index') }}" class="mt-3 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-1">
            All active listings
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</p>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['orders'] }}</p>
        <a href="{{ route('owner.orders.index') }}" class="mt-3 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-1">
            All time orders
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Pending Orders -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Orders</p>
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['pending_orders'] }}</p>
        <a href="{{ route('owner.orders.index') }}" class="mt-3 text-xs font-semibold text-slate-400 hover:text-amber-600 transition-colors flex items-center gap-1">
            Requires your action
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between group hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Revenue</p>
            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-800">${{ number_format($stats['revenue'], 2) }}</p>
        <p class="mt-3 text-xs font-semibold text-slate-400">All time earnings</p>
    </div>
</div>

<!-- Middle Row: Promo + Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Grow Your Business Banner -->
    <div class="lg:col-span-2 relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 rounded-2xl p-7 overflow-hidden text-white shadow-md group">
        <!-- Decorative blobs -->
        <div class="absolute right-0 bottom-0 w-64 h-64 opacity-10 translate-x-12 translate-y-12 group-hover:scale-110 transition duration-500">
            <svg viewBox="0 0 200 200" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M38.5,-65.2C51.9,-60.2,66,-52.9,71.6,-41.3C77.2,-29.8,74.3,-14.9,73.3,-0.6C72.4,13.7,73.4,27.3,67.7,38C62,48.6,49.6,56.1,37,60.9C24.4,65.7,11.5,67.8,-1.8,70.6C-15.2,73.4,-28.4,76.9,-40.4,72.5C-52.4,68.2,-63.2,56,-68.3,42.1C-73.5,28.3,-73.1,12.8,-71.5,-2C-69.8,-16.8,-67.1,-30.9,-59.5,-41.4C-51.9,-51.9,-39.4,-58.8,-27.1,-64.5C-14.8,-70.2,-2.7,-74.7,9.9,-73.5C22.5,-72.3,25.1,-70.2,38.5,-65.2Z" transform="translate(100 100)" />
            </svg>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black tracking-tight leading-tight">Grow your business 🚀</h2>
            <p class="text-indigo-200 text-sm mt-2 max-w-md">Keep your products updated and manage orders smoothly to grow more.</p>
            @if ($business)
                <a href="{{ route('products.create', $business) }}"
                   class="mt-6 inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-5 py-2.5 rounded-xl text-sm shadow hover:bg-indigo-50 transition focus:outline-none focus:ring-2 focus:ring-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add New Product
                </a>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-slate-800 mb-5">Quick Actions</h2>
        <div class="grid grid-cols-2 gap-3">
            @if ($business)
            <a href="{{ route('products.create', $business) }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-indigo-50 hover:border-indigo-100 border border-transparent transition group focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-700 transition text-center">Add Product</span>
            </a>
            @endif

            <a href="{{ route('owner.products.index') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-100 border border-transparent transition group focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-600 group-hover:text-emerald-700 transition text-center">Manage Products</span>
            </a>

            <a href="{{ route('owner.orders.index') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-amber-50 hover:border-amber-100 border border-transparent transition group focus:outline-none focus:ring-2 focus:ring-amber-500">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-600 group-hover:text-amber-700 transition text-center">View Orders</span>
            </a>

            <a href="{{ route('owner.analytics') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-violet-50 hover:border-violet-100 border border-transparent transition group focus:outline-none focus:ring-2 focus:ring-violet-500">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition">
                    <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-600 group-hover:text-violet-700 transition text-center">View Analytics</span>
            </a>
        </div>
    </div>
</div>

<!-- Bottom Row: Recent Orders + Sales Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Customer Orders Table -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-bold text-slate-800">Recent Customer Orders</h2>
            <a href="{{ route('owner.orders.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline flex items-center gap-1">
                View all orders
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto -mx-6 px-6">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 text-slate-400 font-semibold text-xs tracking-wider">
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Product</th>
                        <th class="pb-3">Qty</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Date & Time</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($recentOrders as $order)
                        @php
                            $words = explode(' ', $order->user_name ?? 'U');
                            $custInitials = '';
                            foreach ($words as $w) { $custInitials .= strtoupper(substr($w, 0, 1)); }
                            $custInitials = substr($custInitials, 0, 2) ?: 'U';
                            $gradients = ['from-pink-400 to-rose-500','from-purple-400 to-indigo-500','from-blue-400 to-cyan-500','from-emerald-400 to-teal-500','from-amber-400 to-orange-500'];
                            $custGrad = $gradients[abs(crc32($order->user_name ?? 'U')) % count($gradients)];

                            $statusMap = [
                                'pending'   => ['label' => 'Pending',   'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                'confirmed' => ['label' => 'Confirmed', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                                'completed' => ['label' => 'Completed', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                            ];
                            $statusInfo = $statusMap[$order->status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-slate-50 text-slate-600 border-slate-200'];
                        @endphp
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $custGrad }} flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                        {{ $custInitials }}
                                    </div>
                                    <span class="font-semibold text-slate-800 text-xs">{{ $order->user_name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 text-slate-500 text-xs">{{ $order->items[0]['product_name'] ?? '—' }}</td>
                            <td class="py-3.5 text-slate-600 text-xs font-semibold">{{ $order->items[0]['quantity'] ?? '—' }}</td>
                            <td class="py-3.5 font-extrabold text-slate-800 text-xs">${{ number_format($order->total_price, 2) }}</td>
                            <td class="py-3.5 text-slate-400 text-xs">{{ $order->created_at->format('M d, Y') }}<br><span class="text-[10px]">{{ $order->created_at->format('h:i A') }}</span></td>
                            <td class="py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $statusInfo['class'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 text-sm font-medium">
                                No orders yet. Share your business to start receiving orders!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentOrders->count() > 0)
        <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
            <span>Showing 1 to {{ $recentOrders->count() }} of {{ $stats['orders'] }} orders</span>
            <a href="{{ route('owner.orders.index') }}" class="font-semibold text-indigo-600 hover:underline">See all →</a>
        </div>
        @endif
    </div>

    <!-- Sales Overview -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-800">Sales Overview</h2>
            <a href="#" class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                View full report
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="mb-4">
            <p class="text-2xl font-black text-slate-800">${{ number_format($stats['revenue'], 2) }}</p>
            <p class="text-xs text-slate-400 mt-0.5">Total Revenue</p>
        </div>

        <!-- Mini SVG Line Chart -->
        @php
            $maxRev = collect($dailyRevenue)->max('revenue') ?: 1;
            $chartH = 100;
            $chartW = 260;
            $pts = [];
            foreach ($dailyRevenue as $i => $d) {
                $x = ($i / 6) * $chartW;
                $y = $chartH - (($d['revenue'] / $maxRev) * $chartH * 0.85) - 5;
                $pts[] = "{$x},{$y}";
            }
            $polyline = implode(' ', $pts);
            $fillPts = "0,{$chartH} " . implode(' ', $pts) . " {$chartW},{$chartH}";
        @endphp

        <div class="mt-4">
            <svg viewBox="0 0 {{ $chartW }} {{ $chartH }}" class="w-full" preserveAspectRatio="none" style="height: 120px;">
                <!-- Grid lines -->
                @foreach ([0.25, 0.5, 0.75, 1] as $frac)
                    <line x1="0" y1="{{ $chartH - $frac * $chartH * 0.85 - 5 }}" x2="{{ $chartW }}" y2="{{ $chartH - $frac * $chartH * 0.85 - 5 }}" stroke="#f1f5f9" stroke-width="1"/>
                @endforeach

                <!-- Fill area -->
                <polygon points="{{ $fillPts }}" fill="url(#chartGrad)" opacity="0.4"/>

                <!-- Line -->
                <polyline points="{{ $polyline }}" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>

                <!-- Data points -->
                @foreach ($dailyRevenue as $i => $d)
                    @php
                        $cx = ($i / 6) * $chartW;
                        $cy = $chartH - (($d['revenue'] / $maxRev) * $chartH * 0.85) - 5;
                    @endphp
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="3.5" fill="#6366f1" stroke="white" stroke-width="2"/>
                @endforeach

                <defs>
                    <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.5"/>
                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>

            <!-- Date Labels -->
            <div class="flex justify-between mt-2">
                @foreach ($dailyRevenue as $d)
                    <span class="text-[9px] text-slate-300 font-medium">{{ $d['date'] }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
