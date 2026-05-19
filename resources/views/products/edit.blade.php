@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    <x-alert />
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf @method('PUT')
        @include('products._form')
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update Product</button>
    </form>
</div>
@endsection
