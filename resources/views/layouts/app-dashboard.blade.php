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
    @php
        $user = auth()->user();
        $words = $user ? explode(' ', $user->name) : [];
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        $initials = substr($initials, 0, 2) ?: 'U';
    @endphp

    <div class="flex min-h-screen bg-slate-50" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Drawer -->
        <div class="fixed inset-0 z-50 flex md:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="sidebarOpen = false"
                 x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            <!-- Sidebar Panel -->
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4 shadow-xl transition duration-300 transform"
                 x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Sidebar Brand -->
                <div class="flex flex-shrink-0 items-center px-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-lg">L</div>
                        <span class="text-lg font-bold text-slate-800 tracking-tight">Local Business <span class="text-indigo-600">Directory</span></span>
                    </div>
                </div>

                <!-- Mobile Sidebar Nav -->
                <div class="mt-8 h-0 flex-1 overflow-y-auto">
                    <nav class="space-y-1.5 px-3">
                        @include('user.partials.sidebar-links')
                    </nav>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar (Always visible on md+) -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 border-r border-slate-100 bg-white">
            <div class="flex flex-col flex-grow pt-6 overflow-y-auto">
                <!-- Brand -->
                <div class="flex items-center flex-shrink-0 px-6 mb-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group focus:outline-none rounded">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-black text-lg group-hover:scale-105 transition">L</div>
                        <span class="text-lg font-extrabold text-slate-800 tracking-tight">Local Business <span class="text-indigo-600">Directory</span></span>
                    </a>
                </div>

                <!-- Sidebar Nav Links -->
                <div class="flex-grow flex flex-col px-4">
                    <nav class="flex-1 space-y-1.5 pb-4">
                        @include('user.partials.sidebar-links')
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="md:pl-64 flex flex-col flex-1 w-full">
            <!-- Content Topbar -->
            <header class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white border-b border-slate-100 justify-between px-4 sm:px-6 lg:px-8 items-center shadow-sm">
                <!-- Hamburger Menu for Mobile -->
                <button @click="sidebarOpen = true" class="p-2 text-slate-500 hover:text-slate-700 md:hidden focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded" aria-label="Open sidebar">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Left Spacing/Search Placeholder -->
                <div class="flex-1 flex items-center max-w-xs md:max-w-md">
                    <div class="relative w-full text-slate-400 focus-within:text-slate-600">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" placeholder="Search..." disabled class="block w-full pl-10 pr-3 py-2 border-transparent text-sm bg-slate-50 rounded-xl placeholder-slate-400 focus:outline-none focus:bg-white focus:border-slate-200 focus:ring-0 cursor-not-allowed">
                    </div>
                </div>

                <!-- Right Profile Actions -->
                <div class="flex items-center gap-3">
                    <!-- Notification Bell Dropdown -->
                    <div class="relative"
                         x-data="{
                             open: false,
                             notifications: [],
                             unread: 0,
                             loading: false,
                             fetchNotifications() {
                                 this.loading = true;
                                 fetch('{{ route('notifications.index') }}', {
                                     headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                 })
                                 .then(r => r.json())
                                 .then(data => {
                                     this.notifications = data;
                                     this.unread = data.length;
                                     this.loading = false;
                                 })
                                 .catch(() => { this.loading = false; });
                             },
                             toggle() {
                                 this.open = !this.open;
                                 if (this.open && this.notifications.length === 0) this.fetchNotifications();
                             },
                             statusColor(status) {
                                 const map = { confirmed: 'text-indigo-600 bg-indigo-50', completed: 'text-emerald-600 bg-emerald-50', cancelled: 'text-rose-600 bg-rose-50' };
                                 return map[status] || 'text-slate-600 bg-slate-50';
                             },
                             statusIcon(status) {
                                 if (status === 'confirmed') return '✓';
                                 if (status === 'completed') return '★';
                                 if (status === 'cancelled') return '✕';
                                 return '●';
                             },
                             statusLabel(status) {
                                 const map = { confirmed: 'Order Confirmed', completed: 'Order Completed', cancelled: 'Order Cancelled' };
                                 return map[status] || status;
                             }
                         }"
                         x-init="fetchNotifications()"
                         @click.outside="open = false">

                        <button @click="toggle()"
                                class="relative p-2 text-slate-400 hover:text-indigo-600 rounded-full hover:bg-slate-50 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                                :aria-expanded="open.toString()"
                                aria-label="View notifications">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unread > 0"
                                  class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white"
                                  x-text="unread > 9 ? '9+' : unread"></span>
                        </button>

                        <!-- Dropdown Panel -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-2 w-80 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden"
                             style="display: none;">

                            <!-- Panel Header -->
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-50">
                                <span class="text-sm font-bold text-slate-800">Notifications</span>
                                <span class="text-xs text-slate-400 font-medium">Order Updates</span>
                            </div>

                            <!-- Loading state -->
                            <div x-show="loading" class="py-8 text-center">
                                <svg class="w-6 h-6 text-indigo-400 animate-spin mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>

                            <!-- Notifications List -->
                            <div x-show="!loading" class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                                <template x-if="notifications.length === 0">
                                    <div class="py-10 text-center px-4">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-500">No notifications yet</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Order updates will appear here</p>
                                    </div>
                                </template>

                                <template x-for="notif in notifications" :key="notif._id">
                                    <div class="flex items-start gap-3 px-4 py-3.5 hover:bg-slate-50 transition-colors cursor-default">
                                        <!-- Status Icon -->
                                        <div :class="`w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5 ${statusColor(notif.status)}`"
                                             x-text="statusIcon(notif.status)"></div>
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 truncate" x-text="statusLabel(notif.status)"></p>
                                            <p class="text-xs text-slate-500 mt-0.5 truncate">
                                                Order from <span class="font-medium text-slate-700" x-text="notif.business_name"></span>
                                            </p>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-xs font-bold text-slate-900" x-text="`$${parseFloat(notif.total_price).toFixed(2)}`"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer -->
                            <div class="border-t border-slate-50 px-4 py-2.5">
                                <a href="{{ route('orders.index') }}"
                                   class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline"
                                   @click="open = false">
                                    View all orders →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Avatar -->
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-indigo-600 text-white font-semibold text-sm ring-2 ring-indigo-50 cursor-default" aria-label="User avatar for {{ $user->name ?? 'Guest' }}">
                        {{ $initials }}
                    </div>
                </div>
            </header>

            <!-- Content Body -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl w-full mx-auto">
                <x-alert />
                @yield('dashboard-content')
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('input', function(e) {
            if (e.target && e.target.tagName === 'INPUT' && (e.target.type === 'text' || e.target.type === 'search')) {
                const nameAttr = e.target.getAttribute('name');
                const idAttr = e.target.getAttribute('id');
                const isNameField = (nameAttr && nameAttr.toLowerCase().includes('name') && !nameAttr.toLowerCase().includes('username')) || 
                                   (idAttr && idAttr.toLowerCase().includes('name') && !idAttr.toLowerCase().includes('username'));
                if (isNameField) {
                    e.target.value = e.target.value.replace(/[0-9]/g, '');
                }
            }
        });
    </script>
</body>
</html>
