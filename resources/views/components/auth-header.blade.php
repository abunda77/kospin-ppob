@props(['title', 'description'])

<div class="flex flex-col gap-2 text-center">
    <div class="flex justify-center mb-4">
       <x-app-logo class="h-10 w-10" />
    </div>
    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
        {{ $title }}
    </h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400">
        {{ $description }}
    </p>
</div>
