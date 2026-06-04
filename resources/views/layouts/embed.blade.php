<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'Donation UI') }}</title>

    @fonts
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { margin: 0; padding: 0; overflow-x: hidden; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: transparent; }
    </style>

    <script>
        (function () {
            function sendHeight () {
                const height = document.documentElement.scrollHeight;
                window.parent.postMessage({ type: 'donation-form-height', height }, '*');
            }

            // Send on load
            window.addEventListener('load', sendHeight);

            // Send when DOM changes (Livewire re-renders, etc.)
            const observer = new MutationObserver(sendHeight);
            observer.observe(document.body, { childList: true, subtree: true, attributes: true });

            // Send on resize
            window.addEventListener('resize', sendHeight);

            // Send periodically to catch late changes
            setInterval(sendHeight, 500);
        })();
    </script>
</head>
<body class="antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
