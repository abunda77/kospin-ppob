<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggle() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        }
    }"
    :class="{ 'dark': darkMode }"
>
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-white font-sans antialiased selection:bg-lime-500 selection:text-white">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col items-center justify-center overflow-hidden bg-zinc-50 dark:bg-zinc-900 lg:flex dark:border-r dark:border-zinc-800">
                <!-- Background Effects -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-[30%] -right-[10%] w-[70%] h-[70%] bg-lime-400/20 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                    <div class="absolute bottom-[10%] -left-[10%] w-[50%] h-[50%] bg-emerald-500/20 rounded-full blur-3xl opacity-30"></div>
                </div>

                <!-- Abstract Visual / Dashboard Mockup from Welcome Page -->
                <div class="relative z-10 perspective-[2000px] scale-75">
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
                        </div>
                    </div>
                </div>

                 <div class="relative z-20 mt-12 text-center">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">Kospin PPOB</h2>
                    <p class="text-zinc-500 dark:text-zinc-400 max-w-xs mx-auto">Solusi pembayaran digital modern, cepat, dan terpercaya.</p>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
