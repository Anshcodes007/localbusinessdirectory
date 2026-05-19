@extends('layouts.app')
@section('content')
@php $business = $owner->businesses->first(); @endphp
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Update Business Owner Details</h1>
    <x-alert />
    <form action="{{ route('admin.business-owners.update', $owner) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium">Business Owner Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name', $owner->name) }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Username</label>
            <input type="text" name="username" value="{{ old('username', $owner->username) }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $owner->email) }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="w-full rounded border-gray-300">
        </div>
        <hr>
        <div>
            <label class="block text-sm font-medium">Business Name</label>
            <input type="text" name="business_name" value="{{ old('business_name', $business->name ?? '') }}" class="w-full rounded border-gray-300" required>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">City</label>
                <input type="text" name="city" value="{{ old('city', $business->city ?? '') }}" class="w-full rounded border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium">State</label>
                <input type="text" name="state" value="{{ old('state', $business->state ?? '') }}" class="w-full rounded border-gray-300" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Business Profile Picture</label>
            @if ($business?->logo)
                <img src="{{ asset('storage/'.$business->logo) }}" class="h-16 mb-2 rounded object-cover" alt="Logo">
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full">
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded w-full">Update Details</button>
    </form>
</div>
@endsection
