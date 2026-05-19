@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-2">Add Product</h1>
    <p class="text-gray-500 mb-6">For: {{ $business->name }}</p>
    <x-alert />
    <form action="{{ route('products.store', $business) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @include('products._form')
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Save Product</button>
    </form>
</div>
@endsection
