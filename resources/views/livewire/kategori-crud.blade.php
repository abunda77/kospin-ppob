<div class="space-y-6">
    {{-- Header & Controls --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
            {{ __('Manajemen Kategori') }}
        </h2>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Cari kategori..." 
                />
            </div>
            <flux:button wire:click="create" class="!bg-gradient-to-r !from-indigo-600 !to-violet-600 hover:!from-indigo-500 hover:!to-violet-500 !text-white !border-0 shadow-lg shadow-indigo-500/20 transition-all duration-200 hover:scale-[1.02] whitespace-nowrap">
                <flux:icon.plus class="w-4 h-4 text-white mr-1" />
                <span>{{ __('Tambah Kategori') }}</span>
            </flux:button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4 text-green-700 dark:bg-green-900/50 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4 text-red-700 dark:bg-red-900/50 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm bg-white dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-900 text-white dark:bg-zinc-800 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Urutan') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Kode') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Nama') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Sub Kategori') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @forelse ($kategoris as $kategori)
                        <tr wire:key="kategori-{{ $kategori->id }}" class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $kategori->urutan }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $kodeUpper = strtoupper($kategori->kode);
                                    $badgeColorData = match(true) {
                                        str_contains($kodeUpper, 'PPOB') => 'bg-indigo-50 text-indigo-700 ring-indigo-700/10 dark:bg-indigo-400/10 dark:text-indigo-400 dark:ring-indigo-400/30',
                                        str_contains($kodeUpper, 'GAME') => 'bg-violet-50 text-violet-700 ring-violet-700/10 dark:bg-violet-400/10 dark:text-violet-400 dark:ring-violet-400/30',
                                        str_contains($kodeUpper, 'PRABAYAR') => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/30',
                                        str_contains($kodeUpper, 'PAKET') || str_contains($kodeUpper, 'DATA') => 'bg-amber-50 text-amber-700 ring-amber-700/10 dark:bg-amber-400/10 dark:text-amber-400 dark:ring-amber-400/30',
                                        str_contains($kodeUpper, 'MONEY') || str_contains($kodeUpper, 'WALLET') => 'bg-cyan-50 text-cyan-700 ring-cyan-700/10 dark:bg-cyan-400/10 dark:text-cyan-400 dark:ring-cyan-400/30',
                                        str_contains($kodeUpper, 'VOUCHER') => 'bg-pink-50 text-pink-700 ring-pink-700/10 dark:bg-pink-400/10 dark:text-pink-400 dark:ring-pink-400/30',
                                        str_contains($kodeUpper, 'TV') || str_contains($kodeUpper, 'STREAMING') => 'bg-rose-50 text-rose-700 ring-rose-700/10 dark:bg-rose-400/10 dark:text-rose-400 dark:ring-rose-400/30',
                                        default => 'bg-zinc-50 text-zinc-600 ring-zinc-500/10 dark:bg-zinc-400/10 dark:text-zinc-400 dark:ring-zinc-400/20',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badgeColorData }}">
                                    {{ $kategori->kode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $kategori->nama }}
                                </div>
                                @if($kategori->deskripsi)
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate max-w-xs">
                                        {{ $kategori->deskripsi }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                    {{ $kategori->sub_kategori_count }} Sub Kategori
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($kategori->aktif)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                                        {{ __('Aktif') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20">
                                        {{ __('Nonaktif') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="edit({{ $kategori->id }})" size="sm" icon="pencil-square" variant="ghost" class="!px-2" title="Edit" />
                                    <flux:button wire:click="confirmDelete({{ $kategori->id }})" size="sm" icon="trash" variant="ghost" class="!px-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <flux:icon.folder-open class="w-8 h-8 opacity-50" />
                                    <p>{{ __('Tidak ada kategori ditemukan.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($kategoris->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>


    {{-- Create/Edit Modal --}}
    <flux:modal wire:model="showModal" class="md:min-w-[32rem]">
        <div class="mb-4">
            <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                {{ $isEditing ? __('Edit Kategori') : __('Tambah Kategori') }}
            </h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $isEditing ? __('Update data kategori.') : __('Tambah kategori baru.') }}
            </p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="kode" label="{{ __('Kode') }}" placeholder="KAT001" />
                <flux:input wire:model="urutan" label="{{ __('Urutan') }}" type="number" min="0" placeholder="0" />
            </div>
            
            <flux:input wire:model="nama" label="{{ __('Nama Kategori') }}" placeholder="Nama kategori" />
            
            <flux:textarea wire:model="deskripsi" label="{{ __('Deskripsi') }}" placeholder="Deskripsi kategori (opsional)" rows="3" />

            <div>
                <flux:switch wire:model="aktif" label="{{ __('Status Aktif') }}" />
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <flux:button wire:click="closeModal" variant="ghost">{{ __('Batal') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ $isEditing ? __('Perbarui') : __('Simpan') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete Confirmation Modal --}}
    <flux:modal wire:model="showDeleteModal" class="md:w-96">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-red-600 dark:text-red-400">{{ __('Konfirmasi Hapus') }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                    {{ __('Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.') }}
                </p>
            </div>
            
            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">{{ __('Batal') }}</flux:button>
                <flux:button wire:click="delete" variant="filled" class="bg-red-600 hover:bg-red-700 text-white border-transparent">{{ __('Hapus') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
