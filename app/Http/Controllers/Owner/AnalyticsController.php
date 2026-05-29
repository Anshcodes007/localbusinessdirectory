<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index(Request $request)
    {
        $data = $this->getAnalyticsData($request);

        return view('owner.analytics.index', $data);
    }

    /**
     * Download analytics as PDF.
     */
    public function downloadPdf(Request $request)
    {
        $data = $this->getAnalyticsData($request);
        $business = $request->user()->businesses()->first();

        $data['businessName'] = $business->name ?? 'My Business';
        $data['ownerName']    = $request->user()->name;
        $data['generatedAt']  = now()->format('F j, Y g:i A');

        $pdf  = Pdf::loadView('owner.analytics.pdf', $data);
        $slug  = Str::slug($business->name ?? 'business');
        $month = strtolower(now()->format('F-Y'));

        return $pdf->download("{$slug}-analytics-report-{$month}.pdf");
    }

    /**
     * Gather all analytics data used by both the view and the PDF.
     */
    private function getAnalyticsData(Request $request): array
    {
        $businessIds = $request->user()
            ->businesses()
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        // ── Orders ──────────────────────────────────────────────
        $allOrders     = Order::whereIn('business_id', $businessIds)->get();
        $nonCancelled  = $allOrders->where('status', '!=', Order::STATUS_CANCELLED);
        $totalRevenue  = $nonCancelled->sum('total_price');
        $totalOrders   = $allOrders->count();
        $pendingOrders = $allOrders->where('status', Order::STATUS_PENDING)->count();

        // Repeat customers (ordered more than once)
        $customerCounts = $allOrders->groupBy('user_id');
        $repeatCustomers = $customerCounts->filter(fn ($group) => $group->count() > 1)->count();

        // ── Reviews ─────────────────────────────────────────────
        $allReviews   = Review::whereIn('business_id', $businessIds)->with('user', 'product')->get();
        $totalReviews = $allReviews->count();
        $avgRating    = $totalReviews > 0 ? round($allReviews->avg('rating'), 1) : 0;

        // KPIs
        $kpis = [
            'totalRevenue'    => $totalRevenue,
            'totalOrders'     => $totalOrders,
            'avgRating'       => $avgRating,
            'totalReviews'    => $totalReviews,
            'repeatCustomers' => $repeatCustomers,
            'pendingOrders'   => $pendingOrders,
        ];

        // ── Rating Breakdown ────────────────────────────────────
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($allReviews as $r) {
            $star = max(1, min(5, (int) $r->rating));
            $distribution[$star]++;
        }
        $verifiedCount = $allReviews->where('verified_purchase', true)->count();
        $verifiedPct   = $totalReviews > 0 ? round(($verifiedCount / $totalReviews) * 100) : 0;

        $ratingBreakdown = [
            'distribution' => $distribution,
            'total'        => $totalReviews,
            'average'      => $avgRating,
            'verifiedPct'  => $verifiedPct,
        ];

        // ── Daily Revenue (30 days) ─────────────────────────────
        $dailyRevenue = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayRev = Order::whereIn('business_id', $businessIds)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $dailyRevenue[] = [
                'date'    => now()->subDays($i)->format('M d'),
                'revenue' => round($dayRev, 2),
            ];
        }

        // ── Review Trend (30 days) ──────────────────────────────
        $reviewTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Review::whereIn('business_id', $businessIds)
                ->whereDate('created_at', $date)
                ->count();
            $reviewTrend[] = [
                'date'  => now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }

        // ── Top & Lowest Rated Products ────────────────────────
        $productIds = Product::whereIn('business_id', $businessIds)->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $productReviews = Review::whereIn('product_id', $productIds)->get()->groupBy('product_id');

        $topRatedProducts = collect();
        $lowestRatedProducts = collect();

        foreach ($productReviews as $pid => $reviews) {
            $product = Product::find($pid);
            if (!$product) continue;
            
            $itemData = [
                'name'        => $product->name,
                'avgRating'   => round($reviews->avg('rating'), 1),
                'reviewCount' => $reviews->count(),
            ];
            
            $topRatedProducts->push($itemData);
            $lowestRatedProducts->push($itemData);
        }
        
        $topRatedProducts = $topRatedProducts->sortByDesc('avgRating')->take(5)->values();
        $lowestRatedProducts = $lowestRatedProducts->sortBy('avgRating')->take(5)->values();

        // ── Best Selling Products ───────────────────────────────
        $productSales = [];
        foreach ($nonCancelled as $order) {
            $items = is_array($order->items) ? $order->items : [];
            foreach ($items as $item) {
                $key = $item['product_name'] ?? ($item['product_id'] ?? 'Unknown');
                if (!isset($productSales[$key])) {
                    $productSales[$key] = ['name' => $item['product_name'] ?? $key, 'unitsSold' => 0, 'revenue' => 0];
                }
                $productSales[$key]['unitsSold'] += (int) ($item['quantity'] ?? 1);
                $productSales[$key]['revenue']   += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
            }
        }
        $bestSellingProducts = collect($productSales)->sortByDesc('revenue')->take(5)->values();

        // ── Recent Reviews ──────────────────────────────────────
        $recentReviews = Review::whereIn('business_id', $businessIds)
            ->with('user', 'product')
            ->latest()
            ->take(10)
            ->get();

        // ── Insights ────────────────────────────────────────────
        $insights = [];
        $insights[] = "You've earned $" . number_format($totalRevenue, 2) . " total revenue from {$totalOrders} orders.";

        if ($avgRating >= 4.5 && $totalReviews > 0) {
            $insights[] = "Your average rating of {$avgRating} is excellent! Keep up the great work.";
        } elseif ($avgRating >= 3.5 && $totalReviews > 0) {
            $insights[] = "Your average rating is {$avgRating}. A few improvements could push you to 5 stars!";
        }

        if ($pendingOrders > 0) {
            $insights[] = "You have {$pendingOrders} pending order" . ($pendingOrders > 1 ? 's' : '') . " that need attention.";
        }

        if ($bestSellingProducts->isNotEmpty()) {
            $top = $bestSellingProducts->first();
            $insights[] = "{$top['name']} is your best seller with $" . number_format($top['revenue'], 2) . " in revenue.";
        }

        if ($totalReviews > 0 && $verifiedPct > 50) {
            $insights[] = "{$verifiedPct}% of your reviews are from verified buyers — great trust signal!";
        }

        if ($repeatCustomers > 0) {
            $insights[] = "You have {$repeatCustomers} repeat customer" . ($repeatCustomers > 1 ? 's' : '') . " — loyalty is growing!";
        }

        // ── Top Positive & Negative Reviews (For PDF) ───────────
        $topPositiveReviews = Review::whereIn('business_id', $businessIds)
            ->where('rating', '>=', 4)
            ->with('user', 'product')
            ->latest()
            ->take(5)
            ->get();

        $topNegativeReviews = Review::whereIn('business_id', $businessIds)
            ->where('rating', '<=', 2)
            ->with('user', 'product')
            ->latest()
            ->take(5)
            ->get();

        $lowRatingWarning = ($avgRating < 3 && $totalReviews > 0);

        return compact(
            'kpis', 'ratingBreakdown', 'dailyRevenue', 'reviewTrend',
            'topRatedProducts', 'lowestRatedProducts', 'bestSellingProducts', 
            'recentReviews', 'insights', 'lowRatingWarning',
            'topPositiveReviews', 'topNegativeReviews'
        );
    }
}
