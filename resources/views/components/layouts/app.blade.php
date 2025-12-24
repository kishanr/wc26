<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#0a0e1a">
    <meta name="description" content="WC26 - Your FIFA World Cup 2026 Companion. Predictions, live scores, and more.">
    
    <title>{{ $title ?? 'WC26' }} - World Cup 2026</title>



    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-card border-0 border-b border-white/10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00f5d4] to-[#3a86ff] flex items-center justify-center">
                        <span class="text-xl font-bold text-[#0a0e1a]">26</span>
                    </div>
                    <span class="text-xl font-bold text-gradient">WC26</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-300 hover:text-white transition">Matches</a>
                    <a href="/predictions" class="text-gray-300 hover:text-white transition">Predictions</a>
                    <a href="{{ route('leagues.index') }}" class="text-gray-300 hover:text-white transition">Leagues</a>
                    <a href="/leaderboard" class="text-gray-300 hover:text-white transition">Global Ranks</a>
                    <a href="/brackets" class="text-gray-300 hover:text-white transition">Bracket</a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full border border-white/10">
                            <span class="hidden sm:block text-sm text-gray-300">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white transition p-2" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    @else
                        <a href="/login" class="btn-secondary text-sm py-2 px-4">Sign In</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="pt-20 pb-24 md:pb-8 min-h-screen">
        {{ $slot }}
    </main>

    <x-layouts.footer />

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav md:hidden py-2 px-4">
        <div class="flex items-center justify-around">
            <a href="/" class="flex flex-col items-center gap-1 py-2 px-4 text-gray-400 hover:text-white {{ request()->routeIs('home') ? 'text-[#00f5d4]' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-xs">Home</span>
            </a>
            <a href="/predictions" class="flex flex-col items-center gap-1 py-2 px-4 text-gray-400 hover:text-white {{ request()->routeIs('predictions') ? 'text-[#00f5d4]' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-xs">Predict</span>
            </a>
            <a href="{{ route('leagues.index') }}" class="flex flex-col items-center gap-1 py-2 px-4 text-gray-400 hover:text-white {{ request()->routeIs('leagues.*') ? 'text-[#00f5d4]' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/> <!-- Fallback or simple icon first? No, let's use trophy -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path> <!-- Heart? No -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path> <!-- Archive/Box? No -->
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /> <!-- Star/Trophy-ish -->
                </svg>
                <span class="text-xs">Leagues</span>
            </a>
            <a href="/brackets" class="flex flex-col items-center gap-1 py-2 px-4 text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                <span class="text-xs">Bracket</span>
            </a>
        </div>
    </nav>

    @livewireScripts
</body>
</html>
