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
    <body class="min-h-screen bg-white dark:bg-zinc-950">
        <flux:sidebar sticky collapsible="mobile" class="border-r border-zinc-200 bg-zinc-50/50 dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid gap-2">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="[&_svg]:text-blue-600 dark:[&_svg]:text-blue-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @can('users.view')
                    <flux:sidebar.group :heading="__('Administration')" class="grid gap-2">
                        <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')" class="[&_svg]:text-indigo-600 dark:[&_svg]:text-indigo-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                            {{ __('Users') }}
                        </flux:sidebar.item>

                        @can('roles.view')
                            <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.*')" class="[&_svg]:text-purple-600 dark:[&_svg]:text-purple-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                                {{ __('Roles') }}
                            </flux:sidebar.item>
                        @endcan
                    </flux:sidebar.group>

                    <flux:sidebar.group :heading="__('Master Data')" class="grid gap-2">
                        <flux:sidebar.item icon="rectangle-stack" :href="route('kategori.index')" :current="request()->routeIs('kategori.*')" class="[&_svg]:text-orange-600 dark:[&_svg]:text-orange-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                            {{ __('Kategori') }}
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="squares-2x2" :href="route('sub-kategori.index')" :current="request()->routeIs('sub-kategori.*')" class="[&_svg]:text-amber-600 dark:[&_svg]:text-amber-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                            {{ __('Sub Kategori') }}
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="cube" :href="route('produk-ppob.index')" :current="request()->routeIs('produk-ppob.*')" class="[&_svg]:text-emerald-600 dark:[&_svg]:text-emerald-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                            {{ __('Produk PPOB') }}
                        </flux:sidebar.item>

                        @can('pelanggan.view')
                            <flux:sidebar.item icon="users" :href="route('pelanggan.index')" :current="request()->routeIs('pelanggan.*')" class="[&_svg]:text-cyan-600 dark:[&_svg]:text-cyan-400 hover:bg-white dark:hover:bg-white/10 hover:shadow-sm transition-all duration-200">
                                {{ __('Pelanggan') }}
                            </flux:sidebar.item>
                        @endcan
                    </flux:sidebar.group>

                @endcan

                @can('network.view')
                    <div 
                        x-data="{ 
                            expanded: localStorage.getItem('network_menu_expanded') === 'true' || {{ request()->routeIs('network.*') ? 'true' : 'false' }},
                            toggle() {
                                this.expanded = !this.expanded;
                                localStorage.setItem('network_menu_expanded', this.expanded);
                            }
                        }" 
                        class="px-4 py-2"
                    >
                        <!-- Dropdown Header -->
                        <button 
                            @click="toggle()"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 hover:bg-white hover:shadow-sm dark:hover:bg-zinc-800 {{ request()->routeIs('network.*') ? 'bg-white shadow-sm text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 dark:text-zinc-400' }}"
                        >
                            <div class="flex items-center gap-3">
                                <flux:icon.signal class="size-5 text-sky-600 dark:text-sky-400" />
                                <span>{{ __('Network Connection') }}</span>
                            </div>
                            <flux:icon.chevron-down 
                                class="size-4 transition-transform duration-200" 
                                ::class="{ 'rotate-180': expanded }"
                            />
                        </button>

                        <!-- Dropdown Items -->
                        <div 
                            x-show="expanded" 
                            x-collapse
                            class="mt-1 space-y-1 pl-4"
                        >
                            <flux:sidebar.item icon="server" :href="route('network.sign-on-vps')" :current="request()->routeIs('network.sign-on-vps')" class="text-sm [&_svg]:text-sky-500 dark:[&_svg]:text-sky-300">
                                {{ __('Sign-On VPS') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="play-circle" :href="route('network.start-tunnel')" :current="request()->routeIs('network.start-tunnel')" class="text-sm [&_svg]:text-teal-500 dark:[&_svg]:text-teal-300">
                                {{ __('Start Tunnel') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="stop-circle" :href="route('network.stop-tunnel')" :current="request()->routeIs('network.stop-tunnel')" class="text-sm [&_svg]:text-red-500 dark:[&_svg]:text-red-300">
                                {{ __('Stop Tunnel') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="globe-alt" :href="route('network.check-ip')" :current="request()->routeIs('network.check-ip')" class="text-sm [&_svg]:text-blue-500 dark:[&_svg]:text-blue-300">
                                {{ __('Check IP Address') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="signal" :href="route('network.check-port')" :current="request()->routeIs('network.check-port')" class="text-sm [&_svg]:text-violet-500 dark:[&_svg]:text-violet-300">
                                {{ __('Check Port Status') }}
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="check-circle" :href="route('network.verify-environment')" :current="request()->routeIs('network.verify-environment')" class="text-sm [&_svg]:text-green-500 dark:[&_svg]:text-green-300">
                                {{ __('Verify Environment') }}
                            </flux:sidebar.item>
                        </div>
                    </div>
                @endcan

                @if(auth()->user()->can('backup.view') || auth()->user()->can('activity_log.view'))
                    <div 
                        x-data="{ 
                            expanded: localStorage.getItem('tools_menu_expanded') === 'true' || {{ request()->routeIs('backup-database.*') || request()->routeIs('activity-log.*') ? 'true' : 'false' }},
                            toggle() {
                                this.expanded = !this.expanded;
                                localStorage.setItem('tools_menu_expanded', this.expanded);
                            }
                        }" 
                        class="px-4 py-2"
                    >
                        <!-- Dropdown Header -->
                        <button 
                            @click="toggle()"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200 hover:bg-white hover:shadow-sm dark:hover:bg-zinc-800 {{ request()->routeIs('backup-database.*') || request()->routeIs('activity-log.*') ? 'bg-white shadow-sm text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 dark:text-zinc-400' }}"
                        >
                            <div class="flex items-center gap-3">
                                <flux:icon.wrench-screwdriver class="size-5 text-rose-600 dark:text-rose-400" />
                                <span>{{ __('Tools') }}</span>
                            </div>
                            <flux:icon.chevron-down 
                                class="size-4 transition-transform duration-200" 
                                ::class="{ 'rotate-180': expanded }"
                            />
                        </button>

                        <!-- Dropdown Items -->
                        <div 
                            x-show="expanded" 
                            x-collapse
                            class="mt-1 space-y-1 pl-4"
                        >
                            @can('backup.view')
                                <flux:sidebar.item icon="circle-stack" :href="route('backup-database.index')" :current="request()->routeIs('backup-database.*')" class="text-sm [&_svg]:text-pink-500 dark:[&_svg]:text-pink-300">
                                    {{ __('Backup Database') }}
                                </flux:sidebar.item>
                            @endcan

                            @can('activity_log.view')
                                <flux:sidebar.item icon="clipboard-document-list" :href="route('activity-log.index')" :current="request()->routeIs('activity-log.*')" class="text-sm [&_svg]:text-yellow-500 dark:[&_svg]:text-yellow-300">
                                    {{ __('Activity Log') }}
                                </flux:sidebar.item>
                            @endcan
                        </div>
                    </div>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>


                <flux:sidebar.item as="button" icon="moon" x-on:click="toggle()" x-show="!darkMode" class="cursor-pointer">
                    {{ __('Dark Mode') }}
                </flux:sidebar.item>

                <flux:sidebar.item as="button" icon="sun" x-on:click="toggle()" x-show="darkMode" class="cursor-pointer" x-cloak>
                    {{ __('Light Mode') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @stack('scripts')
        @fluxScripts
    </body>
</html>
