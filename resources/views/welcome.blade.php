<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kospin PPOB') }} - Solusi Pembayaran Digital</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white dark:bg-zinc-950 text-zinc-900 dark:text-white font-sans selection:bg-lime-500 selection:text-white h-full flex flex-col">

    <!-- Header / Navigation -->
    <header id="main-header" class="fixed w-full z-50 top-0 transition-all duration-300">
        <div id="header-bg" class="absolute inset-0 bg-white/70 dark:bg-zinc-950/70 backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 transition-opacity duration-300 opacity-0"></div>
        <div class="relative max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-lime-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-lime-500/20 text-white font-bold text-xl">
                    K
                </div>
                <span class="text-xl font-bold tracking-tight text-zinc-900 dark:text-white">Kospin<span class="text-lime-600 dark:text-lime-400">PPOB</span></span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-6">
                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                <x-button variant="floating" size="sm" href="{{ route('register') }}">
                                    Daftar Sekarang
                                </x-button>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center relative pt-32 pb-20 overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] bg-lime-400/20 rounded-full blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute bottom-[10%] -left-[10%] w-[50%] h-[50%] bg-emerald-500/20 rounded-full blur-3xl opacity-30"></div>
        </div>

        <div class="relative w-full max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <!-- Text Content -->
            <div class="max-w-2xl text-center lg:text-left mx-auto lg:mx-0">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-300 text-xs font-semibold mb-6 border border-lime-200 dark:border-lime-800">
                    <span class="w-2 h-2 rounded-full bg-lime-500 animate-pulse"></span>
                    Platform PPOB Terpercaya #1
                </div>
                
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight mb-8 text-zinc-900 dark:text-white leading-[1.1]">
                    Solusi Pembayaran <br class="hidden lg:block"/>
                    <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-lime-500 to-emerald-600 pb-2">Digital Modern</span>
                </h1>
                
                <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Nikmati kemudahan transaksi pulsa, paket data, PLN, PDAM, dan pembayaran lainnya dalam satu aplikasi yang cepat, aman, dan menguntungkan.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <x-button variant="floating" size="lg" href="{{ route('register') }}" class="w-full sm:w-auto group">
                        Mulai Gratis
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </x-button>
                    <x-button variant="secondary" size="lg" href="#features" class="w-full sm:w-auto">
                        Pelajari Lebih Lanjut
                    </x-button>
                </div>
                
                <div class="mt-10 flex items-center justify-center lg:justify-start gap-6 text-zinc-500 dark:text-zinc-500 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Transaksi 24/7
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Harga Termurah
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Aman & Cepat
                    </div>
                </div>
            </div>

            <!-- Abstract Visual / Dashboard Mockup -->
            <div class="relative hidden lg:block perspective-[2000px]">
                <div class="absolute inset-0 bg-gradient-to-tr from-lime-500/20 to-emerald-500/20 rounded-full blur-3xl transform rotate-12 scale-75"></div>
                
                <!-- Mockup Card -->
                <div class="relative z-10 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-xl border border-white/20 dark:border-white/10 p-6 rounded-3xl shadow-2xl [transform:rotateY(-12deg)_rotateX(6deg)] transition-transform hover:[transform:rotateY(-6deg)_rotateX(3deg)] duration-700">
                    <!-- Fake Dashboard UI -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-700"></div>
                            <div class="space-y-2">
                                <div class="w-24 h-3 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                                <div class="w-16 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-zinc-200 dark:bg-zinc-700"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-lime-500 to-lime-600 text-white shadow-lg">
                            <div class="w-8 h-8 rounded-lg bg-white/20 mb-3"></div>
                            <div class="w-16 h-2 bg-white/40 rounded-full mb-2"></div>
                            <div class="w-20 h-4 bg-white/80 rounded-full"></div>
                        </div>
                        <div class="p-4 rounded-2xl bg-white dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700 shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 mb-3"></div>
                            <div class="w-16 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full mb-2"></div>
                            <div class="w-20 h-4 bg-zinc-300 dark:bg-zinc-600 rounded-full"></div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-red-100 text-red-500"></div>
                                <div class="w-24 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                            </div>
                            <div class="w-12 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-blue-100 text-blue-500"></div>
                                <div class="w-24 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                            </div>
                            <div class="w-12 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-zinc-800/50 border border-zinc-100 dark:border-zinc-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-orange-100 text-orange-500"></div>
                                <div class="w-24 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                            </div>
                            <div class="w-12 h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50">
        <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                &copy; {{ date('Y') }} Kospin PPOB. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">Contact</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('scroll', function() {
            const headerBg = document.getElementById('header-bg');
            if (window.scrollY > 20) {
                headerBg.classList.remove('opacity-0');
                headerBg.classList.add('opacity-100');
            } else {
                headerBg.classList.remove('opacity-100');
                headerBg.classList.add('opacity-0');
            }
        });
    </script>
</body>
</html>
