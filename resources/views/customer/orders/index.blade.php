@extends(auth()->check() && auth()->user()->isUser() ? 'layouts.app-dashboard' : 'layouts.public')

@section('title', 'My Orders - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-alert />

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">My Orders</h1>
        <p class="text-slate-500 mt-1.5 text-sm sm:text-base">Track and manage your orders from local businesses.</p>
    </div>

    <!-- Status Tabs -->
    @php
        $currentStatus = request('status');
        $tabs = [
            ['label' => 'All Orders', 'value' => null],
            ['label' => 'Completed', 'value' => 'completed'],
            ['label' => 'Pending', 'value' => 'pending'],
            ['label' => 'Cancelled', 'value' => 'cancelled'],
        ];
    @endphp
    <div class="border-b border-slate-100 flex gap-6 mb-8 overflow-x-auto" role="tablist">
        @foreach ($tabs as $tab)
            @php
                $isActive = $currentStatus === $tab['value'];
                $url = $tab['value'] ? route('orders.index', ['status' => $tab['value']]) : route('orders.index');
            @endphp
            <a href="{{ $url }}" 
               class="border-b-2 pb-4 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 whitespace-nowrap {{ $isActive ? 'text-indigo-600 border-indigo-600' : 'text-slate-400 hover:text-slate-600 border-transparent hover:border-slate-300' }}"
               role="tab" 
               aria-selected="{{ $isActive ? 'true' : 'false' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Orders Stack -->
    <div class="space-y-6">
        @forelse ($orders as $order)
            <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <!-- Card Header -->
                <div class="flex justify-between items-start gap-4 pb-4 border-b border-slate-50">
                    <div>
                        <h2 class="font-extrabold text-slate-800 text-base md:text-lg group-hover:text-indigo-600 transition-colors">{{ $order->business_name }}</h2>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    <x-status-badge :status="$order->status" />
                </div>

                <!-- Order Items -->
                <ul class="divide-y divide-slate-50 py-2 my-2 text-sm" aria-label="Products ordered">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between items-center py-2.5">
                            <span class="text-slate-600 font-medium">
                                {{ $item['product_name'] }}
                                <span class="text-slate-400 font-normal ml-1">x{{ $item['quantity'] }}</span>
                            </span>
                            <span class="text-slate-800 font-extrabold">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                <!-- Card Footer -->
                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 pt-4 border-t border-slate-50 mt-2">
                    <div class="flex items-center gap-1.5">
                        <span class="text-slate-400 text-sm">Total paid:</span>
                        <span class="text-lg font-black text-indigo-600">${{ number_format($order->total_price, 2) }}</span>
                    </div>

                    @if ($order->isCancellable())
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Cancel this order? Stock will be restored.')" class="flex">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full sm:w-auto text-rose-600 hover:text-rose-700 font-bold text-sm bg-rose-50 hover:bg-rose-100/50 transition-colors px-4 py-2 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500" aria-label="Cancel order with {{ $order->business_name }}">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">No orders found</h3>
                <p class="text-slate-400 mt-1.5 text-sm max-w-md mx-auto">You have no orders matching this status tab. Browse products and place an order to get started!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
