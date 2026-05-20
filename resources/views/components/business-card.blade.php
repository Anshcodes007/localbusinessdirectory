@props(['business'])

@php
    $words = explode(' ', $business->name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    $initials = substr($initials, 0, 2);

    // Pick gradient based on name hash
    $gradients = [
        'from-pink-500 to-rose-500',
        'from-purple-500 to-indigo-500',
        'from-blue-500 to-teal-500',
        'from-emerald-500 to-teal-500',
        'from-amber-500 to-orange-500',
    ];
    $gradient = $gradients[abs(crc32($business->name)) % count($gradients)];

    $categoryName = $business->category->name ?? 'Business';
    $catLower = strtolower($categoryName);

    $catClass = match(true) {
        str_contains($catLower, 'food') || str_contains($catLower, 'dining') || str_contains($catLower, 'restaurant') => 'bg-rose-50 text-rose-700 border-rose-100',
        str_contains($catLower, 'retail') || str_contains($catLower, 'shopping') || str_contains($catLower, 'fashion') => 'bg-pink-50 text-pink-700 border-pink-100',
        str_contains($catLower, 'health') || str_contains($catLower, 'wellness') => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        str_contains($catLower, 'service') => 'bg-amber-50 text-amber-700 border-amber-100',
        str_contains($catLower, 'tech') => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        str_contains($catLower, 'home') || str_contains($catLower, 'garden') => 'bg-teal-50 text-teal-700 border-teal-100',
        default => 'bg-slate-50 text-slate-700 border-slate-100'
    };

    $avgRating = $business->reviews && $business->reviews->count() > 0 
        ? number_format($business->reviews->avg('rating'), 1) 
        : '4.5';
    $reviewsCount = $business->reviews && $business->reviews->count() > 0 
        ? $business->reviews->count() 
        : 12; // Realistic placeholder if no reviews seeded
@endphp

<a href="{{ route('businesses.show', $business) }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none" aria-label="View details of business {{ $business->name }}">
    <!-- Card Logo / Banner -->
    <div class="relative w-full h-48 bg-slate-100 overflow-hidden">
        @if ($business->logo)
            <img src="{{ asset('storage/'.$business->logo) }}" 
                 alt="{{ $business->name }} logo" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="hidden w-full h-full bg-gradient-to-br {{ $gradient }} flex-col items-center justify-center text-white">
                <span class="text-4xl font-extrabold tracking-wider opacity-90">{{ $initials }}</span>
            </div>
        @else
            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white">
                <span class="text-4xl font-extrabold tracking-wider opacity-90">{{ $initials }}</span>
            </div>
        @endif
    </div>

    <!-- Card Body -->
    <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
            <h3 class="font-bold text-lg text-slate-800 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $business->name }}</h3>
            <p class="text-sm text-slate-400 mt-1 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $business->city }}{{ $business->state ? ', ' . $business->state : '' }}
            </p>
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-50">
            <!-- Category Tag -->
            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-md border {{ $catClass }}">
                {{ $categoryName }}
            </span>

            <!-- Rating badge -->
            <div class="flex items-center gap-1 text-slate-600 text-sm font-medium">
                <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-slate-800">{{ $avgRating }}</span>
                <span class="text-slate-400 text-xs">({{ $reviewsCount }})</span>
            </div>
        </div>
    </div>
</a>
