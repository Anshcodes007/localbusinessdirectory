@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Add Category</h1>
    <x-alert />
    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border-gray-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" rows="3" class="w-full rounded border-gray-300">{{ old('description') }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Create</button>
    </form>
</div>
@endsection
