<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}
    public function index(Request $request)
    {
        $query = Product::with(['business', 'category']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('city')) {
            $query->whereHas('business', function ($q) use ($request) {
                $q->where('city', 'like', '%'.$request->city.'%');
            });
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['business', 'category']);

        return view('products.show', compact('product'));
    }

    public function create(Business $business)
    {
        $this->authorizeBusinessOwner($business);
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('business', 'categories'));
    }

    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessOwner($business);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[^0-9]+$/'],
            'description' => ['required', 'string'],
            'category_id' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'gst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'quantity' => ['required', 'integer', 'min:0'],
            'stock_status' => ['required', 'in:in_stock,low_stock,out_of_stock'],
            'image' => ['nullable', 'image', 'max:2048'],
        ], [
            'name.regex' => 'Product name must not contain numbers.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['gst'] = $validated['gst'] ?? 0;
        $validated['business_id'] = $business->id;
        $product = new Product($validated);
        $this->orderService->syncStockStatus($product);
        $business->products()->save($product);

        return redirect()->route('owner.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('business');
        $this->authorizeBusinessOwner($product->business);
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->load('business');
        $this->authorizeBusinessOwner($product->business);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[^0-9]+$/'],
            'description' => ['required', 'string'],
            'category_id' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'gst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'quantity' => ['required', 'integer', 'min:0'],
            'stock_status' => ['required', 'in:in_stock,low_stock,out_of_stock'],
            'image' => ['nullable', 'image', 'max:2048'],
        ], [
            'name.regex' => 'Product name must not contain numbers.',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['gst'] = $validated['gst'] ?? 0;
        $product->fill($validated);
        $this->orderService->syncStockStatus($product);
        $product->save();

        return redirect()->route('owner.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->load('business');
        $this->authorizeBusinessOwner($product->business);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $businessId = $product->business_id;
        $product->delete();

        return redirect()->route('businesses.show', $businessId)
            ->with('success', 'Product deleted successfully.');
    }

    private function authorizeBusinessOwner(Business $business): void
    {
        $user = auth()->user();

        if ((string) $business->user_id !== (string) $user->id) {
            abort(403, 'You can only manage products for your own businesses.');
        }
    }
}
