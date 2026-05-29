<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class OrderReviewController extends Controller
{
    /**
     * Store reviews for the business and products in an order.
     */
    public function store(Request $request, Order $order)
    {
        // Authorize that the user owns this order
        if ((string) $order->user_id !== (string) auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only completed or cancelled orders can be reviewed
        if (!in_array($order->status, ['completed', 'cancelled'], true)) {
            return back()->with('error', 'Only completed or cancelled orders can be reviewed.');
        }

        // Filter out product reviews that are not actually being submitted.
        // A product review is submitted if a title/comment is provided or the rating was modified from default 5.
        if ($order->status === 'completed' && $request->has('product_reviews') && is_array($request->product_reviews)) {
            $filtered = [];
            foreach ($request->product_reviews as $productId => $productData) {
                $rating = (int) ($productData['rating'] ?? 5);
                $title = trim($productData['title'] ?? '');
                $comment = trim($productData['comment'] ?? '');

                if ($title !== '' || $comment !== '' || $rating !== 5) {
                    $filtered[$productId] = $productData;
                }
            }
            $request->merge(['product_reviews' => $filtered]);
        }

        $validationRules = [
            'business_rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'business_title'   => ['required', 'string', 'max:100'],
            'business_comment' => ['required', 'string', 'max:1000'],
        ];

        // For completed orders, we also allow/validate product reviews
        if ($order->status === 'completed') {
            $validationRules['product_reviews'] = ['nullable', 'array'];
            $validationRules['product_reviews.*.rating'] = ['required', 'integer', 'min:1', 'max:5'];
            $validationRules['product_reviews.*.title'] = ['required', 'string', 'max:100'];
            $validationRules['product_reviews.*.comment'] = ['required', 'string', 'max:1000'];
        }

        $validated = $request->validate($validationRules);

        // 1. Create/Update Business Review
        $isCompleted = ($order->status === 'completed');

        Review::updateOrCreate(
            [
                'user_id'     => (string) auth()->id(),
                'order_id'    => (string) $order->id,
                'business_id' => (string) $order->business_id,
                'product_id'  => null,
            ],
            [
                'rating'            => (int) $validated['business_rating'],
                'title'             => $validated['business_title'],
                'comment'           => $validated['business_comment'],
                'verified_purchase' => $isCompleted,
            ]
        );

        // 2. Create/Update Product Reviews (Only for completed orders)
        if ($isCompleted && !empty($validated['product_reviews'])) {
            $allowedProductIds = collect($order->items)->pluck('product_id')->map(fn($id) => (string) $id)->toArray();

            foreach ($validated['product_reviews'] as $productId => $productData) {
                if (in_array((string) $productId, $allowedProductIds, true)) {
                    Review::updateOrCreate(
                        [
                            'user_id'     => (string) auth()->id(),
                            'order_id'    => (string) $order->id,
                            'business_id' => (string) $order->business_id,
                            'product_id'  => (string) $productId,
                        ],
                        [
                            'rating'            => (int) $productData['rating'],
                            'title'             => $productData['title'],
                            'comment'           => $productData['comment'],
                            'verified_purchase' => true,
                        ]
                    );
                }
            }
        }

        return back()->with('success', 'Feedback submitted successfully!');
    }

    /**
     * Update a single review.
     */
    public function update(Request $request, Review $review)
    {
        if ((string) $review->user_id !== (string) auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'title'   => ['required', 'string', 'max:100'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'rating'  => (int) $validated['rating'],
            'title'   => $validated['title'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review updated successfully!');
    }
}
