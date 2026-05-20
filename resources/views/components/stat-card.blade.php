@props(['count', 'label', 'color' => 'indigo', 'link' => '#', 'linkText' => 'View details'])

@php
    $bgClass = match($color) {
        'green' => 'bg-emerald-50 text-emerald-600',
        'yellow' => 'bg-amber-50 text-amber-600',
        'red' => 'bg-rose-50 text-rose-600',
        default => 'bg-indigo-50 text-indigo-600'
    };
@endphp

<div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-full">
    <div>
        <div class="flex justify-between items-start mb-4">
            <span class="text-sm font-medium text-slate-500">{{ $label }}</span>
            <div class="p-2.5 rounded-xl {{ $bgClass }}">
                {{ $slot }}
            </div>
        </div>
        <p class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ $count }}</p>
    </div>
    <div class="mt-6 pt-4 border-t border-slate-50">
        <a href="{{ $link }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline flex items-center gap-1 group focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:outline-none rounded" aria-label="{{ $linkText }} for {{ $label }}">
            {{ $linkText }}
            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</div>
