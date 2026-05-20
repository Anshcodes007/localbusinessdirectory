<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a business review.
     */
    public function store(Request $request, Business $business)
    {
        $user = $request->user();

        // Owners cannot review their own business
        if ((string) $business->user_id === (string) $user->id) {
            return back()->withErrors(['review' => 'You cannot review your own business.']);
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'title'   => ['nullable', 'string', 'max:120'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        // Check if user has any completed order from this business
        $verifiedPurchase = Order::where('user_id', (string) $user->id)
            ->where('business_id', (string) $business->id)
            ->where('status', 'completed')
            ->exists();

        Review::updateOrCreate(
            [
                'user_id'    => (string) $user->id,
                'business_id'=> (string) $business->id,
                'product_id' => null,
            ],
            array_merge($validated, [
                'business_id'       => (string) $business->id,
                'product_id'        => null,
                'verified_purchase' => $verifiedPurchase,
            ])
        );

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }

    /**
     * Store a product review.
     */
    public function storeProduct(Request $request, Product $product)
    {
        $user = $request->user();

        // Owners cannot review products from their own business
        if ($product->business && (string) $product->business->user_id === (string) $user->id) {
            return back()->withErrors(['review' => 'You cannot review your own products.']);
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'title'   => ['nullable', 'string', 'max:120'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        // Verified purchase — user has a completed order with this product
        $verifiedPurchase = Order::where('user_id', (string) $user->id)
            ->where('status', 'completed')
            ->get()
            ->contains(function ($order) use ($product) {
                return collect($order->items)->contains('product_id', (string) $product->id);
            });

        Review::updateOrCreate(
            [
                'user_id'    => (string) $user->id,
                'product_id' => (string) $product->id,
            ],
            array_merge($validated, [
                'business_id'       => (string) $product->business_id,
                'product_id'        => (string) $product->id,
                'verified_purchase' => $verifiedPurchase,
            ])
        );

        return back()->with('success', 'Thank you! Your product review has been submitted.');
    }
}
