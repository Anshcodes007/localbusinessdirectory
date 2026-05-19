<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with(['owner', 'category'])->latest()->paginate(15);

        return view('admin.businesses.index', compact('businesses'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $business->update($validated);

        return back()->with('success', 'Business status updated.');
    }

    public function destroy(Business $business)
    {
        $business->products()->delete();
        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business deleted successfully.');
    }
}
