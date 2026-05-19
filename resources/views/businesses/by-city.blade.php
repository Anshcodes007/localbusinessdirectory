@extends('layouts.public')
@section('title', 'Search Results')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-2">Business Search Results</h1>
    <p class="text-gray-500 mb-6">
        @if ($city) City: <strong>{{ $city }}</strong> @endif
        @if ($state) State: <strong>{{ $state }}</strong> @endif
        — {{ $businesses->total() }} found
    </p>
    <form action="{{ route('businesses.by-city') }}" method="GET" class="flex flex-wrap gap-2 mb-8 max-w-2xl">
        <input type="text" name="city" value="{{ $city }}" placeholder="City" class="flex-1 rounded border-gray-300 min-w-[120px]">
        <input type="text" name="state" value="{{ $state }}" placeholder="State" class="flex-1 rounded border-gray-300 min-w-[120px]">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Search</button>
    </form>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($businesses as $business)
            <a href="{{ route('businesses.show', $business) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md">
                @if ($business->logo)
                    <img src="{{ asset('storage/'.$business->logo) }}" class="w-full h-40 object-cover" alt="{{ $business->name }}">
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400 text-sm">No photo</div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold">{{ $business->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $business->city }}, {{ $business->state }}</p>
                </div>
            </a>
        @empty
            <p class="text-gray-500 col-span-full">No businesses found for this location.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $businesses->links() }}</div>
</div>
@endsection
