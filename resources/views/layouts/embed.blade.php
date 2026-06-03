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

    <style>
        html, body { margin: 0; padding: 0; overflow-x: hidden; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: transparent; }
    </style>
</head>
<body class="antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
