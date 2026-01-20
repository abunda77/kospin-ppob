@props([
    'variant' => 'primary', // primary, secondary, outline, gradient, glass, icon
    'size' => 'md', // sm, md, lg
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm rounded-lg',
        'md' => 'px-6 py-3 text-base rounded-xl',
        'lg' => 'px-8 py-4 text-lg rounded-2xl',
    ];
    
    $variantClasses = [
        'primary' => 'bg-gradient-to-r from-lime-500 to-emerald-600 text-white hover:shadow-lg hover:shadow-lime-500/30 hover:brightness-110 active:scale-95',
        
        'secondary' => 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:shadow-md active:scale-95',
        
        'outline' => 'bg-transparent border-2 border-lime-500 text-lime-600 dark:text-lime-400 hover:bg-lime-50 dark:hover:bg-lime-900/20 hover:shadow-md hover:shadow-lime-500/20 active:scale-95',
        
        'gradient' => 'bg-gradient-to-r from-lime-400 via-emerald-500 to-teal-600 text-white hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-105 active:scale-100',
        
        'glass' => 'bg-white/10 dark:bg-zinc-900/30 backdrop-blur-xl border border-white/20 dark:border-zinc-700/50 text-zinc-900 dark:text-white hover:bg-white/20 dark:hover:bg-zinc-800/40 hover:shadow-lg active:scale-95',
        
        'icon' => 'bg-zinc-50 dark:bg-zinc-800/50 text-zinc-700 dark:text-zinc-300 hover:bg-lime-50 dark:hover:bg-lime-900/30 hover:text-lime-600 dark:hover:text-lime-400 hover:shadow-md active:scale-95 border border-zinc-200 dark:border-zinc-700',
        
        'floating' => 'bg-gradient-to-br from-lime-500 to-emerald-600 text-white shadow-lg shadow-lime-500/30 hover:shadow-2xl hover:shadow-lime-500/40 hover:-translate-y-1 active:translate-y-0',
        
        'danger' => 'bg-gradient-to-r from-red-500 to-rose-600 text-white hover:shadow-lg hover:shadow-red-500/30 hover:brightness-110 active:scale-95',
        
        'ghost' => 'bg-transparent text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white active:scale-95',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
