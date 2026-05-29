@extends('layouts.public')
@section('title', $business->name)
@section('content')
@php
    // Compute review stats
    $reviews = $business->reviews()->forBusiness($business->id)->with('user')->latest()->get();
    $totalReviews = $reviews->count();
    $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
    $fullStars = (int) floor($avgRating);
    $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    foreach ($reviews as $r) {
        $star = max(1, min(5, (int) $r->rating));
        $distribution[$star]++;
    }
    $verifiedCount = $reviews->where('verified_purchase', true)->count();
    $verifiedPct = $totalReviews > 0 ? round(($verifiedCount / $totalReviews) * 100) : 0;
@endphp

<div class="max-w-7xl mx-auto px-4 py-8" x-data="{ showReviewModal: false }">
    <x-alert />

    {{-- ─── BUSINESS HEADER CARD ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="md:flex">
            {{-- Logo --}}
            <div class="md:w-72 flex-shrink-0">
                @if ($business->logo)
                    <img src="{{ asset('storage/'.$business->logo) }}"
                         alt="{{ $business->name }}"
                         class="w-full h-56 md:h-full object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-56 md:h-full bg-gradient-to-br from-indigo-500 to-violet-600 items-center justify-center">
                        <span class="text-5xl font-black text-white/80">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                    </div>
                @else
                    <div class="w-full h-56 md:h-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <span class="text-5xl font-black text-white/80">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-6 md:p-8 flex-1">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-800 mb-1">{{ $business->name }}</h1>
                        <div class="flex items-center gap-3">
                            @if ($business->category)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">{{ $business->category->name }}</span>
                            @endif
                            @if ($totalReviews > 0)
                                <div class="flex items-center gap-1.5">
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $fullStars ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $avgRating }}</span>
                                    <span class="text-xs text-slate-400">({{ $totalReviews }} {{ Str::plural('review', $totalReviews) }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <p class="text-slate-600 mb-5 leading-relaxed">{{ $business->description }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-5">
                    <div class="flex items-center gap-2 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $business->city }}@if($business->state), {{ $business->state }}@endif
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $business->phone }}
                    </div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $business->email }}
                    </div>
                    @if($business->address)
                    <div class="flex items-center gap-2 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ $business->address }}
                    </div>
                    @endif
                </div>

                @auth
                    @if ((string) $business->user_id === (string) auth()->id())
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('businesses.edit', $business) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <a href="{{ route('products.create', $business) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add Product
                            </a>
                            <form action="{{ route('businesses.destroy', $business) }}" method="POST" onsubmit="return confirm('Delete this business?')">
                                @csrf @method('DELETE')
                                <button class="inline-flex items-center gap-1.5 bg-rose-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-rose-700 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- ─── PRODUCTS SECTION ─── --}}
    <h2 class="text-2xl font-extrabold text-slate-800 mb-5">Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
        @forelse ($business->products as $product)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow group">
                @if ($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-44 bg-gradient-to-br from-indigo-100 to-violet-100 items-center justify-center">
                        <span class="text-3xl font-black text-indigo-300">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                    </div>
                @else
                    <div class="w-full h-44 bg-gradient-to-br from-indigo-100 to-violet-100 flex items-center justify-center">
                        <span class="text-3xl font-black text-indigo-300">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                    </div>
                @endif
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-slate-800">{{ $product->name }}</h3>
                        <span class="text-indigo-600 font-extrabold text-lg">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $product->stock_status === 'in_stock' ? 'bg-emerald-50 text-emerald-700' : ($product->stock_status === 'low_stock' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                            {{ str_replace('_', ' ', ucfirst($product->stock_status)) }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $product->quantity }} left</span>
                    </div>
                    @auth
                        @if (auth()->user()->isCustomer() && $product->isInStock())
                            <form action="{{ route('orders.store', $product) }}" method="POST" class="flex gap-2 items-end">
                                @csrf
                                <div class="flex-1">
                                    <label class="block text-[10px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">Qty</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">Order</button>
                            </form>
                        @elseif (auth()->user()->isCustomer())
                            <p class="text-xs text-rose-600 font-medium">Out of stock</p>
                        @endif
                    @else
                        <p class="text-xs text-slate-500"><a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Log in</a> to place an order.</p>
                    @endauth
                    <a href="{{ route('products.show', $product) }}" class="text-xs text-indigo-600 font-bold mt-3 inline-block hover:underline">View details →</a>
                </div>
            </div>
        @empty
            <p class="text-slate-400 col-span-full text-center py-8">No products listed yet.</p>
        @endforelse
    </div>

    {{-- ─── REVIEWS SECTION ─── --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-2xl font-extrabold text-slate-800">Customer Reviews</h2>
        @auth
            @if (auth()->user()->isCustomer() && (string) $business->user_id !== (string) auth()->id())
                <button @click="showReviewModal = true"
                        class="mt-3 sm:mt-0 inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Write a Review
                </button>
            @endif
        @endauth
    </div>

    @error('review')
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium mb-5">{{ $message }}</div>
    @enderror

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
        {{-- Rating Summary Panel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="text-center mb-5">
                <p class="text-5xl font-black text-slate-800">{{ $avgRating }}</p>
                <div class="flex justify-center mt-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $fullStars ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-sm text-slate-400 mt-1">{{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}</p>
            </div>

            <div class="space-y-2.5">
                @for ($star = 5; $star >= 1; $star--)
                    @php $pct = $totalReviews > 0 ? round(($distribution[$star] / $totalReviews) * 100) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-slate-500 w-3">{{ $star }}</span>
                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-medium text-slate-500 w-7 text-right">{{ $distribution[$star] }}</span>
                    </div>
                @endfor
            </div>

            @if ($totalReviews > 0)
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-medium text-slate-500">{{ $verifiedPct }}% verified purchases</span>
                </div>
            @endif
        </div>

        {{-- Reviews List --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse ($reviews as $review)
                @php
                    $words = explode(' ', $review->user->name ?? 'U');
                    $initials = '';
                    foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
                    $initials = substr($initials, 0, 2);
                    $hash = crc32($review->user->name ?? 'U');
                    $gradients = ['from-indigo-500 to-violet-500', 'from-emerald-500 to-teal-500', 'from-amber-500 to-orange-500', 'from-rose-500 to-pink-500', 'from-sky-500 to-cyan-500'];
                    $gradient = $gradients[abs($hash) % count($gradients)];
                    $rStars = (int) $review->rating;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ $initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="font-bold text-sm text-slate-800">{{ $review->user->name ?? 'User' }}</span>
                                @if ($review->verified_purchase)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Verified Purchase
                                    </span>
                                @endif
                                <span class="text-[10px] text-slate-400">{{ $review->created_at ? $review->created_at->diffForHumans() : '' }}</span>
                            </div>
                            <div class="flex mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $rStars ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            @if ($review->title)
                                <p class="font-bold text-sm text-slate-800 mb-1">{{ $review->title }}</p>
                            @endif
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
                    <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">No reviews yet</p>
                    <p class="text-xs text-slate-400 mt-1">Be the first to share your experience!</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ─── REVIEW MODAL ─── --}}
    @auth
    @if (auth()->user()->isCustomer() && (string) $business->user_id !== (string) auth()->id())
    <div x-show="showReviewModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showReviewModal = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10"
             x-data="{ hoverRating: 0, selectedRating: 5 }"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <button @click="showReviewModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-lg font-extrabold text-slate-800 mb-1">Write a Review</h3>
            <p class="text-sm text-slate-400 mb-5">Share your experience with {{ $business->name }}</p>

            <form action="{{ route('reviews.store', $business) }}" method="POST">
                @csrf
                <input type="hidden" name="rating" :value="selectedRating">

                {{-- Star Picker --}}
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rating</label>
                    <div class="flex gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    @mouseenter="hoverRating = {{ $i }}"
                                    @mouseleave="hoverRating = 0"
                                    @click="selectedRating = {{ $i }}"
                                    class="focus:outline-none transition-transform hover:scale-110">
                                <svg class="w-8 h-8 transition-colors"
                                     :class="(hoverRating || selectedRating) >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>

                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Title <span class="text-slate-300">(optional)</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Summarize your experience" class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Comment --}}
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Your Review</label>
                    <textarea name="comment" rows="4" required placeholder="Tell others about your experience..." class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('comment') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
                    Submit Review
                </button>
            </form>
        </div>
    </div>
    @endif
    @endauth
</div>
@endsection
