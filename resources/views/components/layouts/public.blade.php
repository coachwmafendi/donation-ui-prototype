<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Donation UI') }}</title>

    @fonts
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-900">

    {{-- Simple header --}}
    <header class="border-b border-slate-100">
        <div class="mx-auto max-w-2xl px-6 py-5">
            <a href="/" class="text-xl font-bold text-slate-900 tracking-tight">
                Donation UI
            </a>
        </div>
    </header>

    {{-- Main content --}}
    <main class="min-h-screen">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    {{-- Simple footer --}}
    <footer class="border-t border-slate-100 py-8">
        <div class="mx-auto max-w-2xl px-6 text-center text-sm text-slate-400">
             Donation UI. All rights reserved.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
