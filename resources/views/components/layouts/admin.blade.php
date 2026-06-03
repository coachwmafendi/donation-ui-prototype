<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f7f7fb] text-slate-900">

    <div 
        x-data="{ sidebarOpen: false, userMenuOpen: false }" 
        class="min-h-screen flex"
    >
        {{-- Mobile overlay --}}
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        {{-- Sidebar --}}
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed z-50 w-64 h-screen bg-white border-r border-slate-200 flex-shrink-0 transition-transform duration-200 ease-in-out"
        >
            <div class="h-full flex flex-col">
                {{-- Logo --}}
                <div class="px-6 py-5 border-b border-slate-200">
                    <a href="/" class="text-xl font-bold text-slate-900">
                        Donation UI
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 py-4 space-y-1">
                    <a
                        href="/dashboard"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ request()->is('dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon name="layout-dashboard" />
                        Dashboard
                    </a>

                    <a
                        href="/donations"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ request()->is('donations') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon name="dollar-sign" />
                        Donations
                    </a>

                    <a
                        href="/campaigns"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ request()->is('campaigns*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon name="target" />
                        Campaigns
                    </a>

                    <a
                        href="/users"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ request()->is('users') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon name="users" />
                        Users
                    </a>
                </nav>

                {{-- Bottom section --}}
                <div class="px-4 py-4 border-t border-slate-200">
                    <div class="flex items-center gap-3 px-4 py-2">
                        <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-sm font-medium text-slate-600">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="text-sm min-w-0">
                            <p class="font-medium text-slate-900 truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <p class="text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="mt-2 px-4">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-red-50 hover:text-red-600 transition">
                            <x-icon name="log-out" class="size-4" />
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
            {{-- Top bar --}}
            <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
                <button
                    @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-lg hover:bg-slate-100"
                >
                    <x-icon name="menu" class="size-6" />
                </button>

                {{-- User menu --}}
                <div class="relative ml-auto">
                    <button
                        @click="userMenuOpen = !userMenuOpen"
                        @click.outside="userMenuOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-slate-100 transition"
                    >
                        <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-sm font-medium text-slate-600">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name ?? 'Guest' }}</p>
                        </div>
                        <x-icon name="chevron-down" class="size-4 text-slate-400" />
                    </button>

                    {{-- Dropdown --}}
                    <div
                        x-show="userMenuOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white shadow-lg py-1 z-50 origin-top-right"
                    >
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>

                        <a href="#" wire:navigate class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                            Profile
                        </a>

                        <a href="/settings" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                            Settings
                        </a>

                        <div class="border-t border-slate-100 mt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6 lg:p-8">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </div>

    @livewireScripts

    <x-toast />
</body>
</html>
