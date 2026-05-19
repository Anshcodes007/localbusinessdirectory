@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">My Businesses</h1>
        <a href="{{ route('businesses.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Add Business</a>
    </div>
    <x-alert />
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">City</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($businesses as $business)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $business->name }}</td>
                        <td class="px-4 py-3">{{ $business->city }}</td>
                        <td class="px-4 py-3">{{ $business->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('businesses.show', $business) }}" class="text-indigo-600">View</a>
                            <a href="{{ route('businesses.edit', $business) }}" class="text-green-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No businesses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $businesses->links() }}</div>
</div>
@endsection
