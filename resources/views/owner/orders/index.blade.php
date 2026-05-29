@extends('layouts.owner-dashboard')

@section('title', 'Customer Orders - ' . config('app.name'))

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Customer Orders</h1>
    <p class="text-slate-500 mt-1.5 text-sm">Review, confirm, and manage all incoming customer orders.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/50 text-slate-400 font-semibold text-xs tracking-wider">
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Qty</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach ($orders as $order)
                    @php
                        $words = explode(' ', $order->user_name ?? 'U');
                        $custInitials = '';
                        foreach ($words as $w) { $custInitials .= strtoupper(substr($w, 0, 1)); }
                        $custInitials = substr($custInitials, 0, 2) ?: 'U';
                        $gradients = ['from-pink-400 to-rose-500','from-purple-400 to-indigo-500','from-blue-400 to-cyan-500','from-emerald-400 to-teal-500','from-amber-400 to-orange-500'];
                        $custGrad = $gradients[abs(crc32($order->user_name ?? 'U')) % count($gradients)];
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ $custGrad }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ $custInitials }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $order->user_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $order->user_email }}</td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $order->items[0]['product_name'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600 font-semibold">{{ $order->items[0]['quantity'] ?? '—' }}</td>
                        <td class="px-6 py-4 font-extrabold text-slate-800">${{ number_format($order->total_price, 2) }}</td>
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            {{ $order->created_at->format('M d, Y') }}<br>
                            <span class="text-[10px]">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if (in_array($order->status, ['completed', 'cancelled'], true))
                                <x-status-badge :status="$order->status" />
                            @else
                                <form action="{{ route('owner.orders.update', $order) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-semibold rounded-lg border border-slate-200 bg-slate-50 text-slate-700 px-3 py-1.5 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-300 cursor-pointer">
                                        @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if ($orders->isEmpty())
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                            <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            No orders yet. Share your products to start receiving orders!
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <div class="border-t border-slate-100 px-6 py-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
