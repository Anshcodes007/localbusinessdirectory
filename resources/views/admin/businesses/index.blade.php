@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Manage Businesses</h1>
    <x-alert />
    <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 text-sm mb-4 inline-block">&larr; Back</a>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Owner</th>
                    <th class="px-4 py-3 text-left">City</th>
                    <th class="px-4 py-3 text-left">Active</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($businesses as $business)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $business->name }}</td>
                        <td class="px-4 py-3">{{ $business->owner->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $business->city }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.businesses.update', $business) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="is_active" onchange="this.form.submit()" class="rounded border-gray-300 text-xs">
                                    <option value="1" @selected($business->is_active)>Active</option>
                                    <option value="0" @selected(! $business->is_active)>Inactive</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('businesses.show', $business) }}" class="text-indigo-600">View</a>
                            <form action="{{ route('admin.businesses.destroy', $business) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $businesses->links() }}</div>
</div>
@endsection
