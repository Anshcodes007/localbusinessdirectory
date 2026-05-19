<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">{{ config('app.name') }}</a>
                <div class="hidden sm:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('businesses.index') }}" class="text-gray-600 hover:text-indigo-600">Businesses</a>
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-indigo-600">Products</a>
                    <a href="{{ route('search.index') }}" class="text-gray-600 hover:text-indigo-600">Search</a>
                    @auth
                        @if (auth()->user()->isUser())
                            <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-indigo-600">My Orders</a>
                        @endif
                        @if (auth()->user()->isBusinessOwner())
                            <a href="{{ route('owner.dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                        @elseif (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-indigo-600">Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-indigo-600">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Log In</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
    <footer class="bg-gray-800 text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}. University Laravel MVC Project.
        </div>
    </footer>
</body>
</html>
