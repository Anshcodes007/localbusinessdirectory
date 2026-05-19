<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::where('is_active', true)->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->city.'%');
        }

        if ($request->filled('state')) {
            $query->where('state', 'like', '%'.$request->state.'%');
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        $businesses = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('businesses.index', compact('businesses', 'categories'));
    }

    public function show(Business $business)
    {
        $business->load(['category', 'owner', 'products', 'reviews.user']);

        return view('businesses.show', compact('business'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('businesses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['user_id'] = $request->user()->id;
        $validated['is_active'] = true;

        Business::create($validated);

        return redirect()->route('businesses.my')
            ->with('success', 'Business created successfully.');
    }

    public function edit(Business $business)
    {
        $this->authorizeOwner($business);
        $categories = Category::orderBy('name')->get();

        return view('businesses.edit', compact('business', 'categories'));
    }

    public function update(Request $request, Business $business)
    {
        $this->authorizeOwner($business);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($business->logo) {
                Storage::disk('public')->delete($business->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $business->update($validated);

        return redirect()->route('businesses.show', $business)
            ->with('success', 'Business updated successfully.');
    }

    public function destroy(Business $business)
    {
        $this->authorizeOwner($business);

        if ($business->logo) {
            Storage::disk('public')->delete($business->logo);
        }

        foreach ($business->products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $business->products()->delete();
        $business->delete();

        return redirect()->route('businesses.my')
            ->with('success', 'Business deleted successfully.');
    }

    public function myBusinesses(Request $request)
    {
        $businesses = Business::where('user_id', $request->user()->id)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('businesses.my', compact('businesses'));
    }

    private function authorizeOwner(Business $business): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && (string) $business->user_id !== (string) $user->id) {
            abort(403, 'You can only manage your own businesses.');
        }
    }
}
