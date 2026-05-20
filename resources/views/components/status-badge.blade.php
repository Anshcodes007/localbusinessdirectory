@props(['status'])

@php
    $status = strtolower($status);
    
    $classes = match($status) {
        'completed', 'in_stock' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'pending', 'low_stock' => 'bg-amber-50 text-amber-700 border-amber-100',
        'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'cancelled', 'out_of_stock' => 'bg-rose-50 text-rose-700 border-rose-100',
        default => 'bg-slate-50 text-slate-700 border-slate-100'
    };

    $label = match($status) {
        'in_stock' => 'In Stock',
        'low_stock' => 'Low Stock',
        'out_of_stock' => 'Out of Stock',
        default => ucfirst($status)
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full border {{ $classes }}" aria-label="Status: {{ $label }}">
    <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', explode(' ', $classes)[1] ?? 'bg-slate-500') }}"></span>
    {{ $label }}
</span>
