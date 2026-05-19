@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">View Products</h1>
        @php $business = auth()->user()->businesses->first(); @endphp
        @if ($business)
            <a href="{{ route('products.create', $business) }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">+ Add Product</a>
        @endif
    </div>
    <x-alert />
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Image</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Discount</th>
                    <th class="px-4 py-3 text-left">GST</th>
                    <th class="px-4 py-3 text-left">Qty</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="w-12 h-12 object-cover rounded" alt="">
                            @else
                                <span class="text-gray-400 text-xs">No image</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3">${{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3">{{ $product->discount ?? 0 }}%</td>
                        <td class="px-4 py-3">{{ $product->gst ?? 0 }}%</td>
                        <td class="px-4 py-3">{{ $product->quantity }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('products.edit', $product) }}" class="text-indigo-600">Update</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No products. Add your first product.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
