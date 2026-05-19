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
            <label class="block text-sm font-medium">Role</label>
            <select name="role" class="w-full rounded border-gray-300" required>
                @foreach (['admin', 'business_owner', 'customer'] as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
