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
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $business->name }}</td>
                        <td class="px-4 py-3">{{ $business->owner->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $business->city }}</td>
                        <td class="px-4 py-3">
                            @if ($business->is_active)
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('businesses.show', $business) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $businesses->links() }}</div>
</div>
@endsection
