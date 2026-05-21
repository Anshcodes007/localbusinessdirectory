<div>
    <label class="block text-sm font-medium">Name</label>
    <input type="text" name="name" value="{{ old('name', $business->name ?? '') }}" class="w-full rounded border-gray-300" required>
</div>
<div>
    <label class="block text-sm font-medium">Description</label>
    <textarea name="description" rows="4" class="w-full rounded border-gray-300" required>{{ old('description', $business->description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-sm font-medium">City</label>
    <input type="text" name="city" value="{{ old('city', $business->city ?? '') }}" class="w-full rounded border-gray-300" required>
</div>
<div>
    <label class="block text-sm font-medium">Address</label>
    <input type="text" name="address" value="{{ old('address', $business->address ?? '') }}" class="w-full rounded border-gray-300" required>
</div>
<div>
    <label class="block text-sm font-medium">Phone</label>
    <input type="text" name="phone" value="{{ old('phone', $business->phone ?? '') }}" class="w-full rounded border-gray-300" required>
</div>
<div>
    <label class="block text-sm font-medium">Email</label>
    <input type="email" name="email" value="{{ old('email', $business->email ?? '') }}" class="w-full rounded border-gray-300" required>
</div>
<div>
    <label class="block text-sm font-medium">Logo</label>
    <input type="file" name="logo" accept="image/*" class="w-full">
    @if (!empty($business?->logo))
        <img src="{{ asset('storage/'.$business->logo) }}" alt="Logo" class="mt-2 h-20 object-cover rounded">
    @endif
</div>
