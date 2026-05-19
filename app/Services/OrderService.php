<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function placeOrder(User $user, Product $product, int $quantity): Order
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be at least 1.',
            ]);
        }

        $product->refresh();
        $business = $product->business;

        if (! $business || ! $business->is_active) {
            throw ValidationException::withMessages([
                'product' => 'This business is not available for orders.',
            ]);
        }

        if ($product->quantity < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$product->quantity} item(s) available in stock.",
            ]);
        }

        $product->quantity -= $quantity;
        $this->syncStockStatus($product);
        $product->save();

        $discount = (float) ($product->discount ?? 0);
        $gst = (float) ($product->gst ?? 0);
        $total = $this->calculateLineTotal($product->price, $quantity, $discount, $gst);

        return Order::create([
            'user_id' => (string) $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'business_id' => (string) $business->id,
            'business_name' => $business->name,
            'items' => [
                [
                    'product_id' => (string) $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'discount' => $discount,
                    'gst' => $gst,
                    'quantity' => $quantity,
                ],
            ],
            'total_price' => $total,
            'status' => Order::STATUS_PENDING,
        ]);
    }

    public function calculateLineTotal(float $price, int $quantity, float $discount = 0, float $gst = 0): float
    {
        $subtotal = $price * $quantity;
        $afterDiscount = $subtotal - ($subtotal * ($discount / 100));
        $withGst = $afterDiscount + ($afterDiscount * ($gst / 100));

        return round($withGst, 2);
    }

    public function cancelOrder(Order $order): void
    {
        if ($order->status === Order::STATUS_CANCELLED) {
            return;
        }

        if (! $order->isCancellable()) {
            throw ValidationException::withMessages([
                'order' => 'This order can no longer be cancelled.',
            ]);
        }

        $this->restoreStock($order);
        $order->update(['status' => Order::STATUS_CANCELLED]);
    }

    public function restoreStock(Order $order): void
    {
        foreach ($order->items ?? [] as $item) {
            $product = Product::find($item['product_id'] ?? null);

            if ($product) {
                $product->quantity += (int) ($item['quantity'] ?? 0);
                $this->syncStockStatus($product);
                $product->save();
            }
        }
    }

    public function syncStockStatus(Product $product): void
    {
        if ($product->quantity <= 0) {
            $product->quantity = 0;
            $product->stock_status = Product::STOCK_OUT;
        } elseif ($product->quantity <= 5) {
            $product->stock_status = Product::STOCK_LOW;
        } else {
            $product->stock_status = Product::STOCK_IN;
        }
    }
}
