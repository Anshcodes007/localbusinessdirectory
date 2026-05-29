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
            @php
                $isCompleted = $order->status === \App\Models\Order::STATUS_COMPLETED;
                $isCancelled = $order->status === \App\Models\Order::STATUS_CANCELLED;
                $showRatingButtons = $isCompleted || $isCancelled;

                $businessReview = null;
                $productReviews = [];
                $isReviewed = false;
                $hasUnreviewedItems = false;

                if ($showRatingButtons) {
                    $businessReview = \App\Models\Review::where('user_id', auth()->id())
                        ->where('order_id', (string) $order->id)
                        ->where('business_id', (string) $order->business_id)
                        ->whereNull('product_id')
                        ->first();

                    $hasUnreviewedItems = !$businessReview;

                    if ($isCompleted) {
                        $allowedProductIds = collect($order->items)->pluck('product_id')->map(fn($id) => (string) $id)->toArray();
                        foreach ($allowedProductIds as $pid) {
                            $prev = \App\Models\Review::where('user_id', auth()->id())
                                ->where('order_id', (string) $order->id)
                                ->where('product_id', $pid)
                                ->first();
                            if ($prev) {
                                $productReviews[$pid] = $prev;
                            }
                        }
                    }

                    $isReviewed = ($businessReview || count($productReviews) > 0);
                }
            @endphp
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
                    <div class="flex items-center gap-2">
                        @if ($showRatingButtons && $hasUnreviewedItems)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                ⭐ Review Pending
                            </span>
                        @endif
                        <x-status-badge :status="$order->status" />
                    </div>
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

                    <div class="flex items-center gap-2 flex-wrap justify-end">
                        <a href="{{ route('businesses.show', $order->business_id) }}" class="text-slate-600 hover:text-indigo-600 font-bold text-sm bg-slate-50 hover:bg-slate-100 transition-colors px-4 py-2 rounded-xl text-center">
                            View Details
                        </a>

                        @if ($showRatingButtons)
                            @if ($isReviewed)
                                <button @click="$dispatch('open-review-modal', { order: {{ json_encode($order) }}, businessReview: {{ json_encode($businessReview) }}, productReviews: {{ json_encode($productReviews) }} })" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm bg-indigo-50 hover:bg-indigo-100/50 transition-colors px-4 py-2 rounded-xl text-center">
                                    Edit Review
                                </button>
                            @else
                                @if ($isCompleted)
                                    <button @click="$dispatch('open-review-modal', { order: {{ json_encode($order) }}, businessReview: null, productReviews: {} })" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm bg-indigo-50 hover:bg-indigo-100/50 transition-colors px-4 py-2 rounded-xl text-center">
                                        Rate Order
                                    </button>
                                @else
                                    <button @click="$dispatch('open-review-modal', { order: {{ json_encode($order) }}, businessReview: null, productReviews: {} })" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm bg-indigo-50 hover:bg-indigo-100/50 transition-colors px-4 py-2 rounded-xl text-center">
                                        Review Experience
                                    </button>
                                @endif
                            @endif
                        @endif

                        @if ($order->isCancellable())
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Cancel this order? Stock will be restored.')" class="inline-flex">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full sm:w-auto text-rose-600 hover:text-rose-700 font-bold text-sm bg-rose-50 hover:bg-rose-100/50 transition-colors px-4 py-2 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500" aria-label="Cancel order with {{ $order->business_name }}">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
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

<!-- Review Modal with Alpine.js -->
<div x-data="{
        showModal: false,
        order: null,
        status: '',
        businessReview: null,
        productReviews: {},
        businessRating: 5,
        businessTitle: '',
        businessComment: '',
        productsData: [],
        businessHover: 0,
        activeAccordion: 'business',
        ratingLabels: {1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent'},
        initModal(data) {
            this.order = data.order;
            this.status = data.order.status;
            this.businessReview = data.businessReview;
            this.productReviews = data.productReviews || {};
            this.activeAccordion = 'business';
            this.businessHover = 0;
            
            if (this.businessReview) {
                this.businessRating = this.businessReview.rating || 5;
                this.businessTitle = this.businessReview.title || '';
                this.businessComment = this.businessReview.comment || '';
            } else {
                this.businessRating = 5;
                this.businessTitle = '';
                this.businessComment = '';
            }

            this.productsData = [];
            if (this.status === 'completed' && this.order.items) {
                this.order.items.forEach(item => {
                    let prev = this.productReviews[item.product_id] || {};
                    this.productsData.push({
                        id: item.product_id,
                        name: item.product_name,
                        rating: prev.rating || 5,
                        title: prev.title || '',
                        comment: prev.comment || '',
                        hover: 0
                    });
                });
            }

            this.showModal = true;
        }
     }"
     @open-review-modal.window="initModal($event.detail)"
     x-cloak
     class="relative z-50"
>
    <!-- Modal Backdrop -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
    ></div>

    <!-- Modal Dialog -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
         class="fixed inset-0 overflow-y-auto"
    >
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg" @click.away="showModal = false">
                <!-- Header -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800" x-text="businessReview ? 'Edit Your Review' : 'Rate Your Experience'"></h3>
                        <p class="text-xs text-slate-400 mt-0.5" x-text="order ? order.business_name : ''"></p>
                    </div>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form :action="'/orders/' + (order ? (order._id || order.id) : '') + '/review'" method="POST" class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                    @csrf
                    
                    <!-- Business Feedback Accordion -->
                    <div class="border border-slate-100 rounded-xl overflow-hidden mb-4">
                        <button type="button" 
                                @click="activeAccordion = (activeAccordion === 'business' ? '' : 'business')"
                                class="w-full bg-slate-50 px-4 py-3 flex items-center justify-between text-sm font-bold text-slate-700 hover:bg-slate-100/50 transition-colors"
                        >
                            <span x-text="status === 'completed' ? '1. Rate Business / Service' : 'Rate Business / Service'"></span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeAccordion === 'business' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="activeAccordion === 'business'" x-transition class="p-4 border-t border-slate-50 space-y-4">
                            <!-- Star Picker -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rating</label>
                                <div class="flex items-center gap-1.5">
                                    <div class="flex gap-1">
                                        <template x-for="star in 5">
                                            <button type="button" 
                                                    @mouseenter="businessHover = star"
                                                    @mouseleave="businessHover = 0"
                                                    @click="businessRating = star"
                                                    class="focus:outline-none transition-transform hover:scale-110"
                                            >
                                                <svg class="w-8 h-8 transition-colors"
                                                     :class="(businessHover || businessRating) >= star ? 'text-amber-400' : 'text-slate-200'"
                                                     fill="currentColor" viewBox="0 0 20 20"
                                                >
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded" x-text="ratingLabels[businessRating]"></span>
                                </div>
                                <input type="hidden" name="business_rating" :value="businessRating">
                            </div>

                            <!-- Title -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Review Title</label>
                                <input type="text" name="business_title" x-model="businessTitle" required placeholder="Summarize your experience" class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <!-- Comment -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Comment</label>
                                <textarea name="business_comment" x-model="businessComment" rows="3" required placeholder="Share details about the customer service, cleanliness, delivery etc." class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Products Accordions (Only if completed) -->
                    <template x-if="status === 'completed'">
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 mt-4">2. Rate Ordered Products</div>
                            
                            <template x-for="(prod, idx) in productsData" :key="prod.id">
                                <div class="border border-slate-100 rounded-xl overflow-hidden mb-3">
                                    <button type="button" 
                                            @click="activeAccordion = (activeAccordion === 'product_' + idx ? '' : 'product_' + idx)"
                                            class="w-full bg-slate-50 px-4 py-3 flex items-center justify-between text-sm font-bold text-slate-700 hover:bg-slate-100/50 transition-colors"
                                    >
                                        <span x-text="prod.name"></span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-normal text-slate-400" x-text="'Rating: ' + prod.rating + '/5'"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="activeAccordion === 'product_' + idx ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </button>
                                    
                                    <div x-show="activeAccordion === 'product_' + idx" x-transition class="p-4 border-t border-slate-50 space-y-4">
                                        <!-- Product Star Picker -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rating</label>
                                            <div class="flex items-center gap-1.5">
                                                <div class="flex gap-1">
                                                    <template x-for="star in 5">
                                                        <button type="button" 
                                                                @mouseenter="prod.hover = star"
                                                                @mouseleave="prod.hover = 0"
                                                                @click="prod.rating = star"
                                                                class="focus:outline-none transition-transform hover:scale-110"
                                                        >
                                                            <svg class="w-8 h-8 transition-colors"
                                                                 :class="(prod.hover || prod.rating) >= star ? 'text-amber-400' : 'text-slate-200'"
                                                                 fill="currentColor" viewBox="0 0 20 20"
                                                            >
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded" x-text="ratingLabels[prod.rating]"></span>
                                            </div>
                                            <input type="hidden" :name="'product_reviews[' + prod.id + '][rating]'" :value="prod.rating">
                                        </div>

                                        <!-- Product Title -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Review Title</label>
                                            <input type="text" :name="'product_reviews[' + prod.id + '][title]'" x-model="prod.title" placeholder="What did you think of the product?" class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>

                                        <!-- Product Comment -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Comment</label>
                                            <textarea :name="'product_reviews[' + prod.id + '][comment]'" x-model="prod.comment" rows="3" placeholder="Describe the quality, flavor, usefulness, size etc." class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Footer Buttons -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-6">
                        <button type="button" @click="showModal = false" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-colors shadow-lg shadow-indigo-600/10">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
