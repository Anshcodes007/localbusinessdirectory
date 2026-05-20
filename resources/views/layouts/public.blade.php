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
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    @if (!($hideNavbar ?? false))
    <nav class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded" aria-label="Local Business Directory Home">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-lg transition-transform group-hover:scale-105">L</div>
                    <span class="text-lg font-extrabold text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">
                        Local Business <span class="text-indigo-600">Directory</span>
                    </span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-8 h-full">
                    <a href="{{ route('businesses.index') }}" class="{{ request()->routeIs('businesses.index') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Businesses</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Products</a>
                    <a href="{{ route('search.index') }}" class="{{ request()->routeIs('search.index') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Search</a>
                    @auth
                        @if (auth()->user()->isUser())
                            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Dashboard</a>
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">My Orders</a>
                        @elseif (auth()->user()->isBusinessOwner())
                            <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Dashboard</a>
                        @elseif (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-indigo-600 border-indigo-600' : 'text-slate-500 hover:text-indigo-600 border-transparent hover:border-slate-300' }} border-b-2 py-5 px-1 text-sm font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Dashboard</a>
                        @endif
                    @endauth
                </div>

                <!-- Right Menu: Notification + Avatar -->
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <button class="relative p-2 text-slate-400 hover:text-indigo-600 rounded-full hover:bg-slate-50 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500" aria-label="Notifications, 2 unread">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white">
                            2
                        </span>
                    </button>

                    @auth
                        @php
                            $words = explode(' ', auth()->user()->name);
                            $initials = '';
                            foreach ($words as $word) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                            $initials = substr($initials, 0, 2);
                        @endphp
                        <!-- Dropdown Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center justify-center w-9 h-9 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 ring-2 ring-indigo-50" aria-label="User menu" aria-expanded="false" :aria-expanded="open.toString()">
                                {{ $initials }}
                            </button>
                            
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-xl shadow-lg py-1 z-50 text-sm" style="display: none;">
                                <span class="block px-4 py-2 text-slate-500 border-b border-slate-50">Hello, {{ auth()->user()->name }}</span>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-slate-50 focus-visible:outline-none focus-visible:bg-slate-50">My Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 focus-visible:outline-none focus-visible:bg-rose-50">Log Out</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded px-1">Log In</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @endif

    <main class="focus:outline-none">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-400 mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group focus-visible:outline-none rounded" aria-label="Local Business Directory Home">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-lg">L</div>
                    <span class="text-base font-extrabold text-white tracking-tight">
                        Local Business <span class="text-indigo-500">Directory</span>
                    </span>
                </a>

                <!-- Footer Links -->
                <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm">
                    <a href="#" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">About Us</a>
                    <a href="#" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">Contact</a>
                    <a href="#" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 rounded">Terms of Service</a>
                </div>

                <!-- Copyright -->
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} Local Business Directory. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
