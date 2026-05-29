<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OwnerOrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        $businessIds = $request->user()->businesses()->pluck('id')->map(fn ($id) => (string) $id);

        $orders = Order::whereIn('business_id', $businessIds)
            ->latest()
            ->paginate(15);

        return view('owner.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorizeOrder($request, $order);

        if (in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED], true)) {
            return back()->with('error', 'Completed or cancelled orders cannot be modified.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $newStatus = $validated['status'];

        if ($newStatus === Order::STATUS_CANCELLED && $order->status !== Order::STATUS_CANCELLED) {
            $this->orderService->cancelOrder($order);

            return back()->with('success', 'Order cancelled and stock restored.');
        }

        if ($order->status === Order::STATUS_CANCELLED && $newStatus !== Order::STATUS_CANCELLED) {
            return back()->with('error', 'Cancelled orders cannot be reactivated.');
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', 'Order status updated.');
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        $businessIds = $request->user()->businesses()->pluck('id')->map(fn ($id) => (string) $id);

        if (! $businessIds->contains((string) $order->business_id)) {
            abort(403, 'You can only manage orders for your own businesses.');
        }
    }
}
