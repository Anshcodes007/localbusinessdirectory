@extends('layouts.owner-dashboard')
@section('title', 'Analytics — ' . config('app.name'))
@section('content')

{{-- ─── PAGE HEADER ─── --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Analytics</h1>
        <p class="text-sm text-slate-500 mt-1">Business performance overview — Last 30 days</p>
    </div>
    <a href="{{ route('owner.analytics.pdf') }}"
       class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Download PDF Report
    </a>
</div>

@if ($lowRatingWarning)
    <div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 p-5 flex items-start gap-3 shadow-sm">
        <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
            <h4 class="font-bold text-sm">Action Needed</h4>
            <p class="text-xs text-amber-700 mt-0.5">⚠ Customer satisfaction is below target. Your average rating is currently {{ $ratingBreakdown['average'] }} stars.</p>
        </div>
    </div>
@endif

{{-- ─── KPI CARDS ─── --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    {{-- Revenue --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Revenue</span>
        </div>
        <p class="text-xl font-extrabold text-slate-800">${{ number_format($kpis['totalRevenue'], 2) }}</p>
    </div>

    {{-- Orders --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Orders</span>
        </div>
        <p class="text-xl font-extrabold text-slate-800">{{ number_format($kpis['totalOrders']) }}</p>
    </div>

    {{-- Avg Rating --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Avg Rating</span>
        </div>
        <div class="flex items-center gap-2">
            <p class="text-xl font-extrabold text-slate-800">{{ $kpis['avgRating'] }}</p>
            <div class="flex">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-3.5 h-3.5 {{ $i <= floor($kpis['avgRating']) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
        </div>
    </div>

    {{-- Total Reviews --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Reviews</span>
        </div>
        <p class="text-xl font-extrabold text-slate-800">{{ number_format($kpis['totalReviews']) }}</p>
    </div>

    {{-- Repeat Customers --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Repeat</span>
        </div>
        <p class="text-xl font-extrabold text-slate-800">{{ number_format($kpis['repeatCustomers']) }}</p>
    </div>

    {{-- Pending Orders --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Pending</span>
        </div>
        <p class="text-xl font-extrabold text-slate-800">{{ number_format($kpis['pendingOrders']) }}</p>
    </div>
</div>

{{-- ─── ROW 2: RATING BREAKDOWN + REVENUE CHART ─── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Rating Breakdown --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Rating Breakdown</h3>
        <div class="text-center mb-5">
            <p class="text-4xl font-black text-slate-800">{{ $ratingBreakdown['average'] }}</p>
            <div class="flex justify-center mt-1.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= floor($ratingBreakdown['average']) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
            <p class="text-xs text-slate-400 mt-1">{{ $ratingBreakdown['total'] }} reviews</p>
        </div>
        <div class="space-y-2">
            @for ($star = 5; $star >= 1; $star--)
                @php $count = $ratingBreakdown['distribution'][$star] ?? 0; $pct = $ratingBreakdown['total'] > 0 ? round(($count / $ratingBreakdown['total']) * 100) : 0; @endphp
                <div class="flex items-center gap-2.5">
                    <span class="text-xs font-bold text-slate-500 w-3">{{ $star }}</span>
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="text-[10px] font-medium text-slate-500 w-6 text-right">{{ $count }}</span>
                </div>
            @endfor
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-xs font-medium text-slate-500">{{ $ratingBreakdown['verifiedPct'] }}% verified</span>
        </div>
    </div>

    {{-- Revenue Trend Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Revenue Trend</h3>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

{{-- ─── ROW 3: REVIEW TREND + TOP RATED ─── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Review Trend --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Review Trend</h3>
        <div class="h-64">
            <canvas id="reviewChart"></canvas>
        </div>
    </div>

    {{-- Top & Lowest Rated Products --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col gap-6">
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-4">Top Rated Products</h3>
            @if ($topRatedProducts->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">No product reviews yet</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="text-left py-2 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                                <th class="text-center py-2 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Rating</th>
                                <th class="text-right py-2 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Reviews</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($topRatedProducts as $p)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 px-2 font-medium text-slate-800">{{ $p['name'] }}</td>
                                    <td class="py-2.5 px-2 text-center">
                                        <div class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full text-xs font-bold">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            {{ $p['avgRating'] }}
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-2 text-right text-slate-500">{{ $p['reviewCount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="border-t border-slate-100 pt-6">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Lowest Rated Products</h3>
            @if ($lowestRatedProducts->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">No product reviews yet</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="text-left py-2 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                                <th class="text-center py-2 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Rating</th>
                                <th class="text-right py-2 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Reviews</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($lowestRatedProducts as $p)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 px-2 font-medium text-slate-800">{{ $p['name'] }}</td>
                                    <td class="py-2.5 px-2 text-center">
                                        <div class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 px-2 py-0.5 rounded-full text-xs font-bold">
                                            <svg class="w-3.5 h-3.5 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            {{ $p['avgRating'] }}
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-2 text-right text-slate-500">{{ $p['reviewCount'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ─── ROW 4: BEST SELLERS + RECENT REVIEWS ─── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Best Selling Products --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Best Selling Products</h3>
        @if ($bestSellingProducts->isEmpty())
            <p class="text-sm text-slate-400 text-center py-8">No sales data yet</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-3 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                            <th class="text-center py-3 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Units Sold</th>
                            <th class="text-right py-3 px-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($bestSellingProducts as $p)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-2 font-medium text-slate-800">{{ $p['name'] }}</td>
                                <td class="py-3 px-2 text-center text-slate-500">{{ $p['unitsSold'] }}</td>
                                <td class="py-3 px-2 text-right font-bold text-emerald-600">${{ number_format($p['revenue'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Recent Reviews --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Recent Customer Reviews</h3>
        @if ($recentReviews->isEmpty())
            <p class="text-sm text-slate-400 text-center py-8">No reviews yet</p>
        @else
            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                @foreach ($recentReviews as $review)
                    @php
                        $words = explode(' ', $review->user->name ?? 'U');
                        $initials = '';
                        foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
                        $initials = substr($initials, 0, 2);
                        $hash = crc32($review->user->name ?? 'U');
                        $gradients = ['from-indigo-500 to-violet-500', 'from-emerald-500 to-teal-500', 'from-amber-500 to-orange-500', 'from-rose-500 to-pink-500', 'from-sky-500 to-cyan-500'];
                        $gradient = $gradients[abs($hash) % count($gradients)];
                    @endphp
                    <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50/50 hover:bg-slate-50 border border-slate-100 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">{{ $initials }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-800">{{ $review->user->name ?? 'User' }}</span>
                                @if ($review->verified_purchase)
                                    <span class="inline-flex items-center gap-0.5 text-[9px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-full">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Verified Purchase
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $review->sentimentClass() }}">{{ $review->sentimentLabel() }}</span>
                            </div>
                            <div class="flex mb-1.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            @if ($review->title)
                                <h4 class="text-xs font-bold text-slate-800 mb-0.5">{{ $review->title }}</h4>
                            @endif
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                            @if ($review->product)
                                <p class="text-[10px] text-indigo-500 font-medium mt-1">Product: {{ $review->product->name }}</p>
                            @endif
                        </div>
                        <span class="text-[10px] text-slate-400 flex-shrink-0">{{ $review->created_at ? $review->created_at->format('M d, Y') : '' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ─── ROW 5: INSIGHTS ─── --}}
@if (count($insights) > 0)
<div class="mb-8">
    <h3 class="text-sm font-bold text-slate-800 mb-4">💡 Smart Insights</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($insights as $insight)
            <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-2xl p-5 border border-indigo-100 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700">{{ $insight }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- ─── CHART.JS ─── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    revenueGradient.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
    revenueGradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($dailyRevenue)->pluck('date')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode(collect($dailyRevenue)->pluck('revenue')) !!},
                borderColor: '#6366f1',
                backgroundColor: revenueGradient,
                fill: true,
                tension: 0.4,
                borderWidth: 2.5,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 2,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 8 } },
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: v => '$' + v } }
            }
        }
    });

    // Review Trend Chart
    new Chart(document.getElementById('reviewChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($reviewTrend)->pluck('date')) !!},
            datasets: [{
                label: 'Reviews',
                data: {!! json_encode(collect($reviewTrend)->pluck('count')) !!},
                backgroundColor: 'rgba(139, 92, 246, 0.5)',
                borderColor: '#8b5cf6',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 8 } },
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, stepSize: 1 } }
            }
        }
    });
});
</script>
@endsection
