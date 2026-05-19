@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-indigo-600 text-white rounded-xl p-6 mb-8">
        <h1 class="text-2xl font-bold">Welcome, Admin {{ auth()->user()->name }}!</h1>
        <p class="text-indigo-100 mt-1">Manage business owners and monitor the platform.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['business_owners'] }}</p>
            <p class="text-sm text-gray-500">Business Owners</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['businesses'] }}</p>
            <p class="text-sm text-gray-500">Businesses</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['products'] }}</p>
            <p class="text-sm text-gray-500">Products</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['orders'] }}</p>
            <p class="text-sm text-gray-500">Orders</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('admin.business-owners.create') }}" class="bg-indigo-600 text-white p-5 rounded-lg shadow hover:bg-indigo-700 text-center font-medium">+ Setup New Business Owner</a>
        <a href="{{ route('admin.business-owners.index') }}" class="bg-white p-5 rounded-lg shadow hover:shadow-md text-center font-medium">View Business Owners ({{ $stats['business_owners'] }})</a>
    </div>
</div>
@endsection
