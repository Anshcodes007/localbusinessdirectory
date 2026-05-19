@extends('layouts.public')
@section('title', 'Businesses')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <x-alert />
    <h1 class="text-3xl font-bold mb-6">Business Listings</h1>
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6 grid grid-cols-1 sm:grid-cols-5 gap-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name..." class="rounded border-gray-300 w-full">
        <input type="text" name="city" value="{{ request('city') }}" placeholder="City" class="rounded border-gray-300 w-full">
        <input type="text" name="state" value="{{ request('state') }}" placeholder="State" class="rounded border-gray-300 w-full">
        <select name="category" class="rounded border-gray-300 w-full">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white rounded px-4 py-2">Filter</button>
    </form>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($businesses as $business)
            <a href="{{ route('businesses.show', $business) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md">
                @if ($business->logo)
                    <img src="{{ asset('storage/'.$business->logo) }}" alt="{{ $business->name }}" class="w-full h-40 object-cover">
                @else
                    <span class="block w-full h-40 bg-gray-200 text-center leading-[10rem] text-gray-400">No logo</span>
                @endif
                <span class="block p-4">
                    <span class="block font-semibold">{{ $business->name }}</span>
                    <span class="block text-sm text-gray-500">{{ $business->city }}</span>
                    @if ($business->category)
                        <span class="block text-xs text-indigo-600 mt-1">{{ $business->category->name }}</span>
                    @endif
                </span>
            </a>
        @empty
            <p class="text-gray-500 col-span-full">No businesses found.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $businesses->withQueryString()->links() }}</div>
</div>
@endsection
