@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-2">Setup Business Owner</h1>
    <p class="text-gray-500 text-sm mb-6">Create login credentials and business profile for a new owner.</p>
    <x-alert />
    <form action="{{ route('admin.business-owners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        <h3 class="font-semibold text-indigo-600">Owner Login</h3>
        <div>
            <label class="block text-sm font-medium">Business Owner Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name') }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full rounded border-gray-300" required>
        </div>
        <hr>
        <h3 class="font-semibold text-indigo-600">Business Details</h3>
        <div>
            <label class="block text-sm font-medium">Business Name</label>
            <input type="text" name="business_name" value="{{ old('business_name') }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Phone Number (10 digits, unique)</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded border-gray-300" placeholder="e.g. 1234567890" required>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">City</label>
                <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium">State</label>
                <input type="text" name="state" value="{{ old('state') }}" class="w-full rounded border-gray-300" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Business Profile Picture</label>
            <input type="file" name="logo" accept="image/*" class="w-full">
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded w-full">Create Business Owner</button>
    </form>
</div>
@endsection
