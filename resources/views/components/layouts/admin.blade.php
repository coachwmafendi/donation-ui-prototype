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
        x-data="{ sidebarOpen: false, accountOpen: false }"
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
                        href="/supporters"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ request()->is('supporters*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon name="user" />
                        Supporters
                    </a>

                    <a
                        href="/recurring"
                        wire:navigate
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition {{ request()->is('recurring') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon name="refresh-cw" />
                        Recurring
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

                {{-- Account --}}
                <div class="border-t border-slate-200 px-4 py-4">
                    <button
                        type="button"
                        @click="accountOpen = !accountOpen"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-lg hover:bg-slate-50 transition"
                    >
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-full bg-slate-200 flex items-center justify-center text-sm font-medium text-slate-600">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ auth()->user()->name ?? 'Guest' }}</span>
                        </div>
                        <x-icon name="chevron-down" class="size-4 text-slate-400 transition-transform" x-bind:class="accountOpen ? 'rotate-180' : ''" />
                    </button>

                    <div x-show="accountOpen" x-collapse class="space-y-1 mt-1">
                        <a href="/settings" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                            <x-icon name="settings" class="size-4" />
                            Settings
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-slate-600 hover:bg-red-50 hover:text-red-600 transition">
                                <x-icon name="log-out" class="size-4" />
                                Sign out
                            </button>
                        </form>
                    </div>
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
