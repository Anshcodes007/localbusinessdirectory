<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'business_id' => $business->id,
            ],
            $validated
        );

        return back()->with('success', 'Review submitted successfully.');
    }
}
