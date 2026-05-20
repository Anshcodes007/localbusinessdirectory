<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report — {{ $businessName }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111827; line-height: 1.5; }
        .header {
            background: #4f46e5;   /* Solid indigo color */
            color: #ffffff;
            padding: 30px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 4px;
            color: #ffffff;
        }

        .header p {
            font-size: 12px;
            color: #ffffff;
            font-weight: 600;
        }

        .header .meta {
            margin-top: 12px;
            font-size: 10px;
            color: #f9fafb;
            font-weight: 500;
            line-height: 1.6;
        }

        .header .meta p {
            color: #f9fafb !important;
            margin: 2px 0;
        }
        .section { margin: 0 25px 20px; }
        .section-title { font-size: 14px; font-weight: 800; color: #111827; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 2px solid #d1d5db; }
        .kpi-grid { display: table; width: 100%; margin-bottom: 20px; }
        .kpi-row { display: table-row; }
        .kpi-cell { display: table-cell; width: 33.33%; padding: 8px; text-align: center; }
        .kpi-box { border: 1px solid #d1d5db; border-radius: 8px; padding: 14px 8px; background: #ffffff; }
        .kpi-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px; color: #374151; font-weight: 700; }
        .kpi-value { font-size: 28px; font-weight: 800; color: #111827; margin-top: 4px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.data-table th { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #374151; font-weight: 700; text-align: left; padding: 8px 10px; border-bottom: 2px solid #d1d5db; }
        table.data-table td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; color: #1f2937; }
        table.data-table tr:last-child td { border-bottom: none; }
        .rating-bar { display: inline-block; height: 8px; background: #fbbf24; border-radius: 4px; }
        .rating-bar-bg { display: inline-block; width: 100px; height: 8px; background: #d1d5db; border-radius: 4px; position: relative; }
        .star { color: #d97706; }
        .badge { display: inline-block; font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px; }
        .badge-positive { background: #d1fae5; color: #065f46; }
        .badge-neutral { background: #fef3c7; color: #92400e; }
        .badge-negative { background: #fee2e2; color: #991b1b; }
        .review-card { padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .review-card:last-child { border-bottom: none; }
        .review-user { font-weight: 700; font-size: 11px; color: #111827; }
        .review-comment { font-size: 10px; color: #374151; margin-top: 3px; }
        .verified { color: #047857; font-size: 9px; font-weight: 700; }
        .footer { margin-top: 30px; padding: 15px 25px; border-top: 2px solid #d1d5db; font-size: 9px; color: #4b5563; text-align: center; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    {{-- ─── HEADER ─── --}}
    <div class="header">
        <h1>{{ $businessName }}</h1>
        <p>Analytics Report</p>
        <div class="meta">
            <p>Owner: {{ $ownerName }}</p>
            <p>Generated: {{ $generatedAt }}</p>
            <p>Period: Last 30 days</p>
        </div>
    </div>

    {{-- ─── KPIs ─── --}}
    <div class="section">
        <div class="section-title">Key Performance Indicators</div>
        <div class="kpi-grid">
            <div class="kpi-row">
                <div class="kpi-cell">
                    <div class="kpi-box">
                        <div class="kpi-label">Total Revenue</div>
                        <div class="kpi-value">${{ number_format($kpis['totalRevenue'], 2) }}</div>
                    </div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-box">
                        <div class="kpi-label">Total Orders</div>
                        <div class="kpi-value">{{ number_format($kpis['totalOrders']) }}</div>
                    </div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-box">
                        <div class="kpi-label">Average Rating</div>
                        <div class="kpi-value">{{ $kpis['avgRating'] }} <span class="star">★</span></div>
                    </div>
                </div>
            </div>
            <div class="kpi-row">
                <div class="kpi-cell">
                    <div class="kpi-box">
                        <div class="kpi-label">Total Reviews</div>
                        <div class="kpi-value">{{ number_format($kpis['totalReviews']) }}</div>
                    </div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-box">
                        <div class="kpi-label">Repeat Customers</div>
                        <div class="kpi-value">{{ number_format($kpis['repeatCustomers']) }}</div>
                    </div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-box">
                        <div class="kpi-label">Pending Orders</div>
                        <div class="kpi-value">{{ number_format($kpis['pendingOrders']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── RATING BREAKDOWN ─── --}}
    <div class="section">
        <div class="section-title">Rating Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Stars</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @for ($star = 5; $star >= 1; $star--)
                    @php $count = $ratingBreakdown['distribution'][$star] ?? 0; $pct = $ratingBreakdown['total'] > 0 ? round(($count / $ratingBreakdown['total']) * 100) : 0; @endphp
                    <tr>
                        <td>{{ $star }} <span class="star">★</span></td>
                        <td>{{ $count }}</td>
                        <td>
                            <span class="rating-bar-bg">
                                <span class="rating-bar" style="width: {{ $pct }}px; position: absolute; top: 0; left: 0;"></span>
                            </span>
                            {{ $pct }}%
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <p style="margin-top: 8px; font-size: 10px; color: #374151; font-weight: 600;">Verified purchases: {{ $ratingBreakdown['verifiedPct'] }}%</p>
    </div>

    {{-- ─── TOP RATED PRODUCTS ─── --}}
    @if ($topRatedProducts->isNotEmpty())
    <div class="section">
        <div class="section-title">Top Rated Products</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Reviews</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topRatedProducts as $p)
                    <tr>
                        <td>{{ $p['name'] }}</td>
                        <td>{{ $p['avgRating'] }} <span class="star">★</span></td>
                        <td>{{ $p['reviewCount'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ─── BEST SELLING PRODUCTS ─── --}}
    @if ($bestSellingProducts->isNotEmpty())
    <div class="section">
        <div class="section-title">Best Selling Products</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bestSellingProducts as $p)
                    <tr>
                        <td>{{ $p['name'] }}</td>
                        <td>{{ $p['unitsSold'] }}</td>
                        <td>${{ number_format($p['revenue'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ─── RECENT REVIEWS ─── --}}
    @if ($recentReviews->isNotEmpty())
    <div class="section page-break">
        <div class="section-title">Recent Reviews</div>
        @foreach ($recentReviews as $review)
            <div class="review-card">
                <div>
                    <span class="review-user">{{ $review->user->name ?? 'User' }}</span>
                    —
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star" style="opacity: {{ $i <= $review->rating ? 1 : 0.2 }}">★</span>
                    @endfor
                    @if ($review->verified_purchase)
                        <span class="verified">✓ Verified</span>
                    @endif
                    @php
                        $sentimentClass = 'badge-neutral';
                        if ($review->rating >= 4) $sentimentClass = 'badge-positive';
                        elseif ($review->rating <= 2) $sentimentClass = 'badge-negative';
                    @endphp
                    <span class="badge {{ $sentimentClass }}">{{ $review->sentimentLabel() }}</span>
                </div>
                @if ($review->title)
                    <div style="font-weight: 700; font-size: 11px; margin-top: 3px;">{{ $review->title }}</div>
                @endif
                <div class="review-comment">{{ Str::limit($review->comment, 200) }}</div>
                @if ($review->product)
                    <div style="font-size: 9px; color: #4338ca; margin-top: 2px; font-weight: 600;">Product: {{ $review->product->name }}</div>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    {{-- ─── INSIGHTS ─── --}}
    @if (count($insights) > 0)
    <div class="section">
        <div class="section-title">Smart Insights</div>
        <ul style="list-style: none; padding: 0;">
            @foreach ($insights as $insight)
                <li style="padding: 6px 0; border-bottom: 1px solid #e5e7eb; font-size: 11px; color: #1f2937;">
                    💡 {{ $insight }}
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ─── FOOTER ─── --}}
    <div class="footer">
        <p>{{ $businessName }} — Analytics Report — Generated on {{ $generatedAt }}</p>
        <p>Local Business Directory</p>
    </div>
</body>
</html>
