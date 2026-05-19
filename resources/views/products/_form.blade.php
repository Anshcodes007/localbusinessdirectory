<div>
    <label class="block text-sm font-medium">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full rounded border-gray-300" required>
</div>
<div>
    <label class="block text-sm font-medium">Description</label>
    <textarea name="description" rows="3" class="w-full rounded border-gray-300" required>{{ old('description', $product->description ?? '') }}</textarea>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Price ($)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium">Quantity Available</label>
        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" class="w-full rounded border-gray-300" required>
    </div>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Discount (%)</label>
        <input type="number" step="0.01" name="discount" value="{{ old('discount', $product->discount ?? 0) }}" class="w-full rounded border-gray-300" min="0" max="100">
    </div>
    <div>
        <label class="block text-sm font-medium">GST (%)</label>
        <input type="number" step="0.01" name="gst" value="{{ old('gst', $product->gst ?? 0) }}" class="w-full rounded border-gray-300" min="0" max="100">
    </div>
</div>
<div>
    <label class="block text-sm font-medium">Stock Status</label>
    <select name="stock_status" class="w-full rounded border-gray-300" required>
        @foreach (['in_stock', 'low_stock', 'out_of_stock'] as $status)
            <option value="{{ $status }}" @selected(old('stock_status', $product->stock_status ?? 'in_stock') === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium">Product Picture</label>
    <input type="file" name="image" accept="image/*" class="w-full">
    @if (!empty($product?->image))
        <img src="{{ asset('storage/'.$product->image) }}" alt="Product" class="mt-2 h-20 object-cover rounded">
    @endif
</div>
