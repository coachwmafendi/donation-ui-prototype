<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f7f7fb] min-h-screen flex items-center justify-center">
    <div class="text-center px-6">
        <h1 class="text-4xl font-bold text-slate-900 mb-4">Donation UI</h1>
        <p class="text-lg text-slate-500 mb-8">Clean, premium donation management</p>
        <div class="flex items-center justify-center gap-4">
            @auth
                <a href="/donations" class="rounded-lg bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="rounded-lg bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                    Sign in
                </a>
                <a href="{{ route('register') }}" class="rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Create account
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
