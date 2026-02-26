<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-8 flex items-center space-x-3">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="h-10 w-10 bg-cyan-500 rounded-xl flex items-center justify-center shadow-md shadow-cyan-500/20 group-hover:scale-105 transition-transform">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-slate-800 tracking-tight">EasyColoc</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white shadow-xl shadow-slate-200/40 sm:rounded-[40px] border border-slate-100 relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
