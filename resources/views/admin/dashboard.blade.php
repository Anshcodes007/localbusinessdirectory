@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="bg-indigo-600 text-white rounded-xl p-6 mb-8">
        <h1 class="text-2xl font-bold">Welcome, Admin {{ auth()->user()->name }}!</h1>
        <p class="text-indigo-100 mt-1">Manage business owners and monitor the platform.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <a href="{{ route('admin.business-owners.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 text-center hover:shadow-md hover:border-indigo-200 transition group block">
            <p class="text-3xl font-extrabold text-indigo-600 group-hover:scale-105 transition-transform">{{ $stats['business_owners'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Owners</p>
        </a>
        <a href="{{ route('admin.users.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 text-center hover:shadow-md hover:border-indigo-200 transition group block">
            <p class="text-3xl font-extrabold text-indigo-600 group-hover:scale-105 transition-transform">{{ $stats['users'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Users</p>
        </a>
        <a href="{{ route('admin.businesses.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 text-center hover:shadow-md hover:border-indigo-200 transition group block">
            <p class="text-3xl font-extrabold text-indigo-600 group-hover:scale-105 transition-transform">{{ $stats['businesses'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Businesses</p>
        </a>
        <a href="{{ route('products.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 text-center hover:shadow-md hover:border-indigo-200 transition group block">
            <p class="text-3xl font-extrabold text-indigo-600 group-hover:scale-105 transition-transform">{{ $stats['products'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Products</p>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 text-center hover:shadow-md hover:border-indigo-200 transition group block">
            <p class="text-3xl font-extrabold text-indigo-600 group-hover:scale-105 transition-transform">{{ $stats['categories'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Categories</p>
        </a>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 text-center block">
            <p class="text-3xl font-extrabold text-indigo-600">{{ $stats['orders'] }}</p>
            <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-wider">Orders</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.business-owners.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-5 rounded-xl shadow-sm font-bold text-center flex items-center justify-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Setup Owner
        </a>
        <a href="{{ route('admin.business-owners.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 p-5 rounded-xl shadow-sm font-bold text-center flex items-center justify-center gap-2 transition">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            View Business Owners
        </a>
        <a href="{{ route('admin.users.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 p-5 rounded-xl shadow-sm font-bold text-center flex items-center justify-center gap-2 transition">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            View Users
        </a>
        <a href="{{ route('admin.businesses.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 p-5 rounded-xl shadow-sm font-bold text-center flex items-center justify-center gap-2 transition">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            View Businesses
        </a>
    </div>
</div>
@endsection
