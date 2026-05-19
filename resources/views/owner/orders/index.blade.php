@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Customer Orders</h1>
        <a href="{{ route('owner.dashboard') }}" class="text-indigo-600 text-sm">&larr; Dashboard</a>
    </div>
    <x-alert />
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Product</th>
                    <th class="px-4 py-3 text-left">Qty</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $order->user_name }}</td>
                        <td class="px-4 py-3">{{ $order->user_email }}</td>
                        <td class="px-4 py-3">{{ $order->items[0]['product_name'] ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->items[0]['quantity'] ?? '-' }}</td>
                        <td class="px-4 py-3">${{ number_format($order->total_price, 2) }}</td>
                        <td class="px-4 py-3">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('owner.orders.update', $order) }}" method="POST" class="flex gap-1 items-center">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="rounded border-gray-300 text-xs">
                                    @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
