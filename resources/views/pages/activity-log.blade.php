<x-layouts::app :title="__('Activity Log')">
    <div class="flex h-full w-full flex-1 flex-col gap-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    {{ __('Activity Log') }}
                </h1>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">
                    Riwayat aktivitas pengguna sistem.
                </p>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden p-6">
            <livewire:activity-log-table />
        </div>
    </div>
</x-layouts::app>
