<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Order Tracking System' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">
                            📦 Order Tracking System
                        </h1>
                        <a href="/admin" class="text-sm text-gray-600 hover:text-gray-900">
                            Admin Panel
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-12">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        © {{ date('Y') }} Order Tracking System. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
        
        @livewireScripts
    </body>
</html>
