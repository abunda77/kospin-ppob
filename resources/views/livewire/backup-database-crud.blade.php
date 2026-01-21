<div class="space-y-6">
    {{-- Header & Controls --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                {{ __('Backup Database') }}
            </h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Kelola backup database SQL Anda.') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            {{-- Search --}}
            <div class="relative w-full sm:w-64">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Search..." 
                />
            </div>

            {{-- Backup Button (Purple/Violet Theme) --}}
            <div wire:loading.remove wire:target="createBackup">
                <flux:button 
                    wire:click="createBackup" 
                    class="!bg-gradient-to-r !from-violet-600 !to-purple-600 hover:!from-violet-500 hover:!to-purple-500 !text-white !border-0 shadow-lg shadow-violet-500/25 transition-all duration-200 hover:scale-[1.02] whitespace-nowrap"
                >
                    <flux:icon.plus class="w-4 h-4 text-white mr-1.5" />
                    <span>{{ __('Backup Sekarang') }}</span>
                </flux:button>
            </div>
            <div wire:loading wire:target="createBackup">
                <flux:button disabled class="!bg-gradient-to-r !from-violet-600 !to-purple-600 !text-white !border-0 opacity-75 cursor-not-allowed whitespace-nowrap">
                    <flux:icon.arrow-path class="animate-spin w-4 h-4 text-white mr-1.5" />
                    <span>{{ __('Memproses...') }}</span>
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4 text-green-700 dark:bg-green-900/50 dark:text-green-300">
            <div class="flex items-center gap-2">
                <flux:icon.check-circle class="w-5 h-5" />
                {{ session('message') }}
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4 text-red-700 dark:bg-red-900/50 dark:text-red-300">
            <div class="flex items-center gap-2">
                <flux:icon.exclamation-circle class="w-5 h-5" />
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="w-12 px-4 py-3">
                            <input type="checkbox" class="rounded border-zinc-300 dark:border-zinc-600" disabled />
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Nama File') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Tipe') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Ukuran') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400 cursor-pointer hover:text-zinc-700 dark:hover:text-zinc-200">
                            {{ __('Tanggal') }}
                            <flux:icon.chevron-down class="w-3 h-3 inline ml-1" />
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @forelse ($backups as $backup)
                        <tr wire:key="backup-{{ $backup->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" class="rounded border-zinc-300 dark:border-zinc-600" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <flux:icon.document class="w-5 h-5 text-zinc-400" />
                                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $backup->file_name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $backup->type === 'manual' ? 'bg-transparent border-zinc-400 text-zinc-600 dark:text-zinc-400' : 'bg-transparent border-blue-400 text-blue-600 dark:text-blue-400' }}">
                                    {{ $backup->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @switch($backup->status)
                                    @case('success')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                            {{ __('success') }}
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                            {{ __('failed') }}
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                            {{ __('processing') }}
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $backup->formatted_file_size }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $backup->created_at->format('d M Y H:i:s') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    @if($backup->status === 'success')
                                        <a 
                                            href="{{ route('backup-database.download', $backup->id) }}"
                                            class="inline-flex items-center gap-1 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                        >
                                            <flux:icon.arrow-down-tray class="w-4 h-4" />
                                            <span>{{ __('Download') }}</span>
                                        </a>
                                    @endif
                                    <button 
                                        wire:click="confirmDelete({{ $backup->id }})" 
                                        class="inline-flex items-center gap-1 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                    >
                                        <flux:icon.trash class="w-4 h-4" />
                                        <span>{{ __('Hapus') }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <flux:icon.circle-stack class="w-10 h-10 opacity-50" />
                                    <p class="text-lg">{{ __('Tidak ada backup ditemukan.') }}</p>
                                    <p class="text-sm text-zinc-400 dark:text-zinc-500">{{ __('Klik tombol "Backup Sekarang" untuk membuat backup baru.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Footer with Pagination --}}
        <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Showing') }} {{ $backups->firstItem() ?? 0 }} {{ __('to') }} {{ $backups->lastItem() ?? 0 }} {{ __('of') }} {{ $backups->total() }} {{ __('results') }}
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Per page') }}</span>
                <select 
                    wire:model.live="perPage" 
                    class="rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 text-sm py-1 px-2"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        
        {{-- Pagination Links --}}
        @if($backups->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $backups->links() }}
            </div>
        @endif
    </div>


    {{-- Delete Confirmation Modal --}}
    <flux:modal wire:model="showDeleteModal" class="md:w-96">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-red-600 dark:text-red-400">{{ __('Konfirmasi Hapus') }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                    {{ __('Apakah Anda yakin ingin menghapus backup ini? File backup akan dihapus permanen dan tidak dapat dikembalikan.') }}
                </p>
            </div>
            
            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">{{ __('Batal') }}</flux:button>
                <flux:button wire:click="delete" variant="filled" class="bg-red-600 hover:bg-red-700 text-white border-transparent">{{ __('Hapus') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
