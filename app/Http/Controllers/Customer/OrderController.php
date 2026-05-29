<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index()
    {
        $status = request('status');
        $userId = (string) auth()->id();
        $query = Order::where('user_id', $userId);

        if ($status && in_array($status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED, Order::STATUS_COMPLETED, Order::STATUS_CANCELLED], true)) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);

        // Check if there are completed orders that have not been reviewed yet.
        $completedOrders = Order::where('user_id', $userId)
            ->where('status', Order::STATUS_COMPLETED)
            ->get();

        $hasUnreviewed = false;
        foreach ($completedOrders as $completedOrder) {
            $isBusinessReviewed = \App\Models\Review::where('user_id', $userId)
                ->where('order_id', (string) $completedOrder->id)
                ->where('business_id', (string) $completedOrder->business_id)
                ->whereNull('product_id')
                ->exists();

            if (!$isBusinessReviewed) {
                $hasUnreviewed = true;
                break;
            }

            $allowedProductIds = collect($completedOrder->items)->pluck('product_id')->map(fn($id) => (string) $id)->toArray();
            foreach ($allowedProductIds as $pid) {
                $isProductReviewed = \App\Models\Review::where('user_id', $userId)
                    ->where('order_id', (string) $completedOrder->id)
                    ->where('product_id', $pid)
                    ->exists();
                if (!$isProductReviewed) {
                    $hasUnreviewed = true;
                    break 2;
                }
            }
        }

        if ($hasUnreviewed && !session()->has('rating_prompt_shown')) {
            session()->flash('success', 'Your order has been completed. Please rate your experience.');
            session()->put('rating_prompt_shown', true);
        }

        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->orderService->placeOrder(
            $request->user(),
            $product,
            (int) $validated['quantity']
        );

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully. Stock has been updated.');
    }

    public function cancel(Order $order)
    {
        if ((string) $order->user_id !== (string) auth()->id()) {
            abort(403);
        }

        $this->orderService->cancelOrder($order);

        return back()->with('success', 'Order cancelled. Stock has been restored.');
    }
}
