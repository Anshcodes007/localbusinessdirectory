@props(['product'])

@php
    $words = explode(' ', $product->name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    $initials = substr($initials, 0, 2);

    $gradients = [
        'from-sky-400 to-indigo-500',
        'from-violet-400 to-fuchsia-500',
        'from-teal-400 to-emerald-500',
        'from-amber-400 to-rose-500',
    ];
    $gradient = $gradients[abs(crc32($product->name)) % count($gradients)];
@endphp

<div class="group relative bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full">
    <!-- Image & Heart Button Overlay -->
    <div class="relative w-full h-44 bg-slate-100 overflow-hidden">
        <a href="{{ route('products.show', $product) }}" class="block w-full h-full focus-visible:outline-none" aria-label="View details of product {{ $product->name }}">
            @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hidden w-full h-full bg-gradient-to-br {{ $gradient }} flex-col items-center justify-center text-white">
                    <span class="text-3xl font-extrabold tracking-wider opacity-90">{{ $initials }}</span>
                </div>
            @else
                <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white">
                    <span class="text-3xl font-extrabold tracking-wider opacity-90">{{ $initials }}</span>
                </div>
            @endif
        </a>

        <!-- Favorite Button Overlay -->
        <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm shadow-sm flex items-center justify-center text-slate-400 hover:text-rose-500 hover:scale-110 active:scale-95 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500" aria-label="Add {{ $product->name }} to favorites">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    </div>

    <!-- Details -->
    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            <a href="{{ route('products.show', $product) }}" class="focus-visible:outline-none rounded">
                <h3 class="font-bold text-sm text-slate-800 hover:text-indigo-600 transition-colors line-clamp-1 group-hover:text-indigo-600">{{ $product->name }}</h3>
            </a>
            @if ($product->business)
                <a href="{{ route('businesses.show', $product->business) }}" class="text-xs text-slate-400 hover:text-indigo-500 hover:underline mt-1 block">
                    {{ $product->business->name }}
                </a>
            @endif
        </div>

        <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
            <span class="text-sm font-extrabold text-slate-900">${{ number_format($product->price, 2) }}</span>
            <x-status-badge :status="$product->stock_status" />
        </div>
    </div>
</div>
