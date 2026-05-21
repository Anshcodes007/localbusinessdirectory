@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Edit User</h1>
    <x-alert />
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Role</label>
            <p class="text-sm font-semibold text-gray-800 bg-gray-50 p-2 rounded border border-gray-200">Customer/User</p>
        </div>
        <div>
            <label class="block text-sm font-medium">New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="w-full rounded border-gray-300">
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded w-full font-bold">Update Details</button>
    </form>
</div>
@endsection
