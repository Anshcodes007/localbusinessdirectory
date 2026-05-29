<?php

use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

function getCustomer()
{
    return User::factory()->create([
        'role' => User::ROLE_USER,
    ]);
}

test('customer can submit review for completed order (business and products)', function () {
    $customer = getCustomer();
    
    $business = Business::create([
        'user_id' => 'owner123',
        'name' => 'Pizza Palace',
        'city' => 'New York',
        'state' => 'NY',
        'email' => 'pizza@example.com',
        'phone' => '1234567890',
        'is_active' => true,
    ]);

    $product = Product::create([
        'business_id' => $business->id,
        'name' => 'Cheese Pizza',
        'description' => 'Good pizza',
        'price' => 15.00,
        'quantity' => 10,
        'stock_status' => 'in_stock',
    ]);

    $order = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [
            [
                'product_id' => (string) $product->id,
                'product_name' => $product->name,
                'price' => 15.00,
                'quantity' => 1,
            ]
        ],
        'total_price' => 15.00,
        'status' => 'completed',
    ]);

    $response = $this->actingAs($customer)->post(route('orders.review.store', $order), [
        'business_rating' => 5,
        'business_title' => 'Amazing Place',
        'business_comment' => 'Great pizza and delivery!',
        'product_reviews' => [
            (string) $product->id => [
                'rating' => 4,
                'title' => 'Delicious',
                'comment' => 'Very cheesy and fresh.',
            ]
        ]
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    // Verify reviews created
    $bReview = Review::where('order_id', (string) $order->id)->whereNull('product_id')->first();
    $this->assertNotNull($bReview);
    $this->assertEquals(5, $bReview->rating);
    $this->assertEquals('Amazing Place', $bReview->title);
    $this->assertEquals('Great pizza and delivery!', $bReview->comment);
    $this->assertTrue($bReview->verified_purchase);

    $pReview = Review::where('order_id', (string) $order->id)->where('product_id', (string) $product->id)->first();
    $this->assertNotNull($pReview);
    $this->assertEquals(4, $pReview->rating);
    $this->assertEquals('Delicious', $pReview->title);
    $this->assertTrue($pReview->verified_purchase);
});

test('customer can submit review for cancelled order (business only, no verified badge, no product review)', function () {
    $customer = getCustomer();

    $business = Business::create([
        'user_id' => 'owner123',
        'name' => 'Burger Joint',
        'city' => 'Chicago',
        'state' => 'IL',
        'email' => 'burger@example.com',
        'phone' => '0987654321',
        'is_active' => true,
    ]);

    $product = Product::create([
        'business_id' => $business->id,
        'name' => 'Hamburger',
        'description' => 'Plain hamburger',
        'price' => 8.00,
        'quantity' => 5,
        'stock_status' => 'in_stock',
    ]);

    $order = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [
            [
                'product_id' => (string) $product->id,
                'product_name' => $product->name,
                'price' => 8.00,
                'quantity' => 1,
            ]
        ],
        'total_price' => 8.00,
        'status' => 'cancelled',
    ]);

    $response = $this->actingAs($customer)->post(route('orders.review.store', $order), [
        'business_rating' => 2,
        'business_title' => 'Cancelled and sad',
        'business_comment' => 'The order was cancelled by the store.',
        'product_reviews' => [
            (string) $product->id => [
                'rating' => 5,
                'title' => 'Super',
                'comment' => 'Never received.',
            ]
        ]
    ]);

    $response->assertSessionHasNoErrors();

    // Business review exists but verified_purchase is false
    $bReview = Review::where('order_id', (string) $order->id)->whereNull('product_id')->first();
    $this->assertNotNull($bReview);
    $this->assertEquals(2, $bReview->rating);
    $this->assertFalse($bReview->verified_purchase);

    // Product review must NOT be created (ignored for cancelled orders)
    $pReview = Review::where('order_id', (string) $order->id)->where('product_id', (string) $product->id)->first();
    $this->assertNull($pReview);
});

test('submitting review twice updates the existing review rather than duplicating', function () {
    $customer = getCustomer();

    $business = Business::create([
        'user_id' => 'owner123',
        'name' => 'Taco Shop',
        'city' => 'Austin',
        'state' => 'TX',
        'email' => 'taco@example.com',
        'phone' => '1122334455',
        'is_active' => true,
    ]);

    $order = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [],
        'total_price' => 0.00,
        'status' => 'cancelled',
    ]);

    // First submission
    $this->actingAs($customer)->post(route('orders.review.store', $order), [
        'business_rating' => 4,
        'business_title' => 'Good service',
        'business_comment' => 'Friendly staff',
    ]);

    $this->assertEquals(1, Review::where('order_id', (string) $order->id)->count());

    // Second submission
    $this->actingAs($customer)->post(route('orders.review.store', $order), [
        'business_rating' => 5,
        'business_title' => 'Updated review title',
        'business_comment' => 'Actually it was perfect!',
    ]);

    // Count is still 1
    $this->assertEquals(1, Review::where('order_id', (string) $order->id)->count());

    $bReview = Review::where('order_id', (string) $order->id)->first();
    $this->assertEquals(5, $bReview->rating);
    $this->assertEquals('Updated review title', $bReview->title);
    $this->assertEquals('Actually it was perfect!', $bReview->comment);
});

test('individual review can be updated using PUT reviews update route', function () {
    $customer = getCustomer();

    $review = Review::create([
        'user_id' => (string) $customer->id,
        'business_id' => 'biz123',
        'rating' => 3,
        'title' => 'Decent',
        'comment' => 'Could be better',
        'verified_purchase' => false,
    ]);

    $response = $this->actingAs($customer)->put(route('reviews.update', $review), [
        'rating' => 5,
        'title' => 'Awesome Update',
        'comment' => 'It got way better!',
    ]);

    $response->assertSessionHasNoErrors();
    $review->refresh();

    $this->assertEquals(5, $review->rating);
    $this->assertEquals('Awesome Update', $review->title);
    $this->assertEquals('It got way better!', $review->comment);
});

test('submitting completed order review with blank product reviews succeeds', function () {
    $customer = getCustomer();
    
    $business = Business::create([
        'user_id' => 'owner123',
        'name' => 'Pizza Palace',
        'city' => 'New York',
        'state' => 'NY',
        'email' => 'pizza@example.com',
        'phone' => '1234567890',
        'is_active' => true,
    ]);

    $product = Product::create([
        'business_id' => $business->id,
        'name' => 'Cheese Pizza',
        'description' => 'Good pizza',
        'price' => 15.00,
        'quantity' => 10,
        'stock_status' => 'in_stock',
    ]);

    $order = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [
            [
                'product_id' => (string) $product->id,
                'product_name' => $product->name,
                'price' => 15.00,
                'quantity' => 1,
            ]
        ],
        'total_price' => 15.00,
        'status' => 'completed',
    ]);

    // Submit rating with empty title/comment for the product (it should be ignored)
    $response = $this->actingAs($customer)->post(route('orders.review.store', $order), [
        'business_rating' => 5,
        'business_title' => 'Amazing Place',
        'business_comment' => 'Great pizza and delivery!',
        'product_reviews' => [
            (string) $product->id => [
                'rating' => 5,
                'title' => '',
                'comment' => '',
            ]
        ]
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    // Verify business review exists
    $this->assertTrue(Review::where('order_id', (string) $order->id)->whereNull('product_id')->exists());
    // Verify product review was NOT created
    $this->assertFalse(Review::where('order_id', (string) $order->id)->whereNotNull('product_id')->exists());
});

test('submitting product review with rating modified but empty title/comment fails validation', function () {
    $customer = getCustomer();
    
    $business = Business::create([
        'user_id' => 'owner123',
        'name' => 'Pizza Palace',
        'city' => 'New York',
        'state' => 'NY',
        'email' => 'pizza@example.com',
        'phone' => '1234567890',
        'is_active' => true,
    ]);

    $product = Product::create([
        'business_id' => $business->id,
        'name' => 'Cheese Pizza',
        'description' => 'Good pizza',
        'price' => 15.00,
        'quantity' => 10,
        'stock_status' => 'in_stock',
    ]);

    $order = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [
            [
                'product_id' => (string) $product->id,
                'product_name' => $product->name,
                'price' => 15.00,
                'quantity' => 1,
            ]
        ],
        'total_price' => 15.00,
        'status' => 'completed',
    ]);

    // Submit with modified rating (4) but no title/comment. This should fail validation!
    $response = $this->actingAs($customer)->post(route('orders.review.store', $order), [
        'business_rating' => 5,
        'business_title' => 'Amazing Place',
        'business_comment' => 'Great pizza and delivery!',
        'product_reviews' => [
            (string) $product->id => [
                'rating' => 4, // modified rating
                'title' => '',
                'comment' => '',
            ]
        ]
    ]);

    $response->assertSessionHasErrors([
        'product_reviews.' . (string) $product->id . '.title',
        'product_reviews.' . (string) $product->id . '.comment',
    ]);
});

test('owner cannot change the status of completed or cancelled orders', function () {
    $owner = User::factory()->create(['role' => User::ROLE_BUSINESS_OWNER]);
    $customer = getCustomer();
    
    $business = Business::create([
        'user_id' => (string) $owner->id,
        'name' => 'Owner Pizza',
        'city' => 'Chicago',
        'state' => 'IL',
        'email' => 'ownerpizza@example.com',
        'phone' => '1234567890',
        'is_active' => true,
    ]);

    $completedOrder = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [],
        'total_price' => 10.00,
        'status' => 'completed',
    ]);

    $cancelledOrder = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [],
        'total_price' => 10.00,
        'status' => 'cancelled',
    ]);

    // Try to update completed order to confirmed
    $response = $this->actingAs($owner)->patch(route('owner.orders.update', $completedOrder), [
        'status' => 'confirmed',
    ]);
    $response->assertSessionHas('error', 'Completed or cancelled orders cannot be modified.');
    $this->assertEquals('completed', $completedOrder->fresh()->status);

    // Try to update cancelled order to pending
    $response = $this->actingAs($owner)->patch(route('owner.orders.update', $cancelledOrder), [
        'status' => 'pending',
    ]);
    $response->assertSessionHas('error', 'Completed or cancelled orders cannot be modified.');
    $this->assertEquals('cancelled', $cancelledOrder->fresh()->status);
});

test('customer cannot cancel completed or cancelled orders', function () {
    $customer = getCustomer();
    
    $business = Business::create([
        'user_id' => 'owner123',
        'name' => 'Pizza House',
        'city' => 'New York',
        'state' => 'NY',
        'email' => 'pizza@example.com',
        'phone' => '1234567890',
        'is_active' => true,
    ]);

    $completedOrder = Order::create([
        'user_id' => (string) $customer->id,
        'user_name' => $customer->name,
        'user_email' => $customer->email,
        'business_id' => (string) $business->id,
        'business_name' => $business->name,
        'items' => [],
        'total_price' => 15.00,
        'status' => 'completed',
    ]);

    $response = $this->actingAs($customer)->patch(route('orders.cancel', $completedOrder));
    $response->assertSessionHas('error', 'Completed or cancelled orders cannot be modified.');
    $this->assertEquals('completed', $completedOrder->fresh()->status);
});
