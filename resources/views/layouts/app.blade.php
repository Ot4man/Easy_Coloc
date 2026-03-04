<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="h-full bg-slate-50 antialiased overflow-hidden">
        <div class="flex h-screen overflow-hidden">
            <aside class="w-72 bg-white border-r border-slate-200 flex flex-col shrink-0">
                <!-- Sidebar Header -->
                <div class="p-8 flex items-center space-x-3">
                    <div class="flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="text-xl font-bold text-slate-800 tracking-tight">EasyColoc</span>
                </div>

                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 space-y-1 mt-2">
                    <a href="{{ route('colocations.create') }}" class="mb-6 mx-2 flex items-center justify-center space-x-2 px-4 py-3 bg-white border border-slate-200 shadow-sm rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all duration-200">
                        <i class="fa-solid fa-plus text-blue-500"></i>
                        <span>New Colocation</span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="group relative flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-cyan-600 bg-cyan-50/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        @if(request()->routeIs('dashboard'))
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-cyan-600 rounded-l-full"></div>
                        @endif
                        <i class="fa-solid fa-house mr-4 text-base {{ request()->routeIs('dashboard') ? 'text-cyan-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                        Home
                    </a>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="group relative flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-cyan-600 bg-cyan-50/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            @if(request()->routeIs('admin.dashboard'))
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-cyan-600 rounded-l-full"></div>
                            @endif
                            <i class="fa-solid fa-chart-bar mr-4 text-base {{ request()->routeIs('admin.dashboard') ? 'text-cyan-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                            Admin Dashboard
                        </a>
                    @endif

                    <a href="{{ route('colocations.index') }}" class="group relative flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('colocations.*') ? 'text-cyan-600 bg-cyan-50/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        @if(request()->routeIs('colocations.*'))
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-cyan-600 rounded-l-full"></div>
                        @endif
                        <i class="fa-solid fa-users mr-4 text-base {{ request()->routeIs('colocations.*') ? 'text-cyan-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                        Colocations
                    </a>

                    <a href="{{ route('profile.edit') }}" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'text-cyan-600 bg-cyan-50/50' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-gear mr-4 text-base {{ request()->routeIs('profile.*') ? 'text-cyan-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                        Settings
                    </a>
                </nav>
                <!-- Sidebar Footer/Reputation -->
                <div class="p-6">
                

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="flex items-center px-2 py-2 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors group">
                            <i class="fa-solid fa-right-from-bracket mr-3 text-base text-slate-400 group-hover:text-slate-600"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col h-full bg-[#f8f9fc] relative">
                <!-- Top Header -->
                <header class="bg-[#f8f9fc] h-20 flex items-center justify-between px-8 shrink-0">
                    <div class="flex items-center space-x-6">
                        <h2 class="text-xl font-semibold text-slate-800">
                            Hello {{ Auth::user()->name }}
                        </h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Recherche" class="pl-10 pr-4 py-2 bg-white text-slate-800 placeholder-slate-400 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 w-64 border border-slate-200 hidden sm:block shadow-sm">
                            <i class="fa-solid fa-magnifying-glass w-4 h-4 text-slate-400 absolute left-3 top-2.5 hidden sm:block text-sm"></i>
                        </div>
                        <div class="h-10 w-10 bg-cyan-500 rounded-xl flex items-center justify-center text-white font-semibold text-sm shadow-sm shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto p-10 scroll-smooth">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
