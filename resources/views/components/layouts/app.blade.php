<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>{{ $title ?? 'Tracking System' }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo-white.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo-white.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo-white.png') }}">

        <!-- Preload Critical Resources -->
        <link rel="dns-prefetch" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if(file_exists(public_path('build/manifest.json')))
            {{-- Production: Use built assets --}}
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            {{-- Development fallback --}}
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            @include('components.layouts.header')

            <!-- Page Content -->
            <main class="flex-1 py-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            @include('components.layouts.footer')
        </div>
    </body>
</html>
