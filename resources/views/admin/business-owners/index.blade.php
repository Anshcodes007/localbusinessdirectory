@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">View Business Owners</h1>
            <p class="text-gray-500 text-sm">Total owners: <strong>{{ $totalOwners }}</strong></p>
        </div>
        <a href="{{ route('admin.business-owners.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">+ Setup Owner</a>
    </div>
    <x-alert />
    <div class="bg-white rounded-lg shadow overflow-x-auto mt-4">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Owner</th>
                    <th class="px-4 py-3 text-left">Username</th>
                    <th class="px-4 py-3 text-left">Business</th>
                    <th class="px-4 py-3 text-left">City / State</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($owners as $owner)
                    @php $b = $owner->businesses->first(); @endphp
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if ($b?->logo)
                                    <img src="{{ asset('storage/'.$b->logo) }}" class="w-10 h-10 rounded object-cover" alt="">
                                @else
                                    <span class="w-10 h-10 rounded bg-gray-200 inline-block"></span>
                                @endif
                                {{ $owner->name }}
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $owner->username ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $b->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $b ? $b->city.', '.$b->state : '-' }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('admin.business-owners.edit', $owner) }}" class="text-indigo-600 font-medium">Update Details</a>
                            <form action="{{ route('admin.business-owners.destroy', $owner) }}" method="POST" class="inline" onsubmit="return confirm('Delete this owner?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No business owners yet. Create one to get started.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $owners->links() }}</div>
</div>
@endsection
