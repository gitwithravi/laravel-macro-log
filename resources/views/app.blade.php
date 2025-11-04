<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#4F46E5">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Macro Log">
        <meta name="application-name" content="Macro Log">
        <meta name="description" content="Track your meals and nutrition goals with AI-powered insights">

        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" href="/favicon.png">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
        <link rel="shortcut icon" type="image/png" href="/favicon.png">

        <!-- PWA Manifest -->
        <link rel="manifest" href="/build/manifest.webmanifest">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                // Register SW immediately for faster PWA prompt on first visit
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful:', registration.scope);

                        // Force activation on first install
                        if (registration.installing) {
                            registration.installing.addEventListener('statechange', function(e) {
                                if (e.target.state === 'activated') {
                                    console.log('Service Worker activated');
                                }
                            });
                        }

                        // Check for updates
                        registration.addEventListener('updatefound', function() {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', function() {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    console.log('New service worker available');
                                    window.dispatchEvent(new CustomEvent('swUpdateAvailable', {
                                        detail: function() {
                                            newWorker.postMessage({ type: 'SKIP_WAITING' });
                                            window.location.reload();
                                        }
                                    }));
                                }
                            });
                        });
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed:', error);
                    });
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
