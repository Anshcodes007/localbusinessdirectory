@extends('layouts.owner-dashboard')

@section('title', 'My Products - ' . config('app.name'))

@section('content')
@php $business = auth()->user()->businesses->first(); @endphp

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">My Products</h1>
        <p class="text-slate-500 mt-1.5 text-sm">Manage and update all your product listings.</p>
    </div>
    @if ($business)
        <a href="{{ route('products.create', $business) }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    @endif
</div>

<x-alert />

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-semibold text-xs tracking-wider">
                    <th class="px-6 py-4">Image</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Discount</th>
                    <th class="px-6 py-4">GST</th>
                    <th class="px-6 py-4">Qty</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($products as $product)
                    @php
                        $words = explode(' ', $product->name);
                        $pInitials = '';
                        foreach ($words as $w) { $pInitials .= strtoupper(substr($w, 0, 1)); }
                        $pInitials = substr($pInitials, 0, 2);
                        $gradients = ['from-sky-400 to-indigo-500','from-violet-400 to-fuchsia-500','from-teal-400 to-emerald-500','from-amber-400 to-rose-500'];
                        $pGrad = $gradients[abs(crc32($product->name)) % count($gradients)];
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     class="w-12 h-12 object-cover rounded-xl"
                                     alt="{{ $product->name }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-12 h-12 rounded-xl bg-gradient-to-br {{ $pGrad }} items-center justify-center text-white text-xs font-bold">{{ $pInitials }}</div>
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $pGrad }} flex items-center justify-center text-white text-xs font-bold">{{ $pInitials }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $product->name }}</td>
                        <td class="px-6 py-4 font-extrabold text-slate-800">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $product->discount ?? 0 }}%</td>
                        <td class="px-6 py-4 text-slate-500">{{ $product->gst ?? 0 }}%</td>
                        <td class="px-6 py-4 font-semibold text-slate-600">{{ $product->quantity }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('products.edit', $product) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <p class="text-slate-400 font-medium">No products yet.</p>
                            @if ($business)
                                <a href="{{ route('products.create', $business) }}" class="mt-3 inline-block text-xs font-bold text-indigo-600 hover:underline">+ Add your first product</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($products->hasPages())
    <div class="border-t border-slate-100 px-6 py-4">{{ $products->links() }}</div>
    @endif
</div>
@endsection
