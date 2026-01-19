<div class="space-y-6">
    {{-- Header & Controls --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
            {{ __('Manajemen Pelanggan') }}
        </h2>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Cari pelanggan..." 
                />
            </div>
            <flux:button wire:click="create" variant="primary" icon="plus">
                {{ __('Tambah Pelanggan') }}
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
    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Nama') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Email') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('No. HP') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Kota') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Status') }}
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @forelse ($pelanggans as $pelanggan)
                        <tr wire:key="pelanggan-{{ $pelanggan->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $pelanggan->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $pelanggan->email ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $pelanggan->no_hp }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $pelanggan->kota ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pelanggan->aktif)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        {{ __('Aktif') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                        {{ __('Nonaktif') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="edit({{ $pelanggan->id }})" size="sm" icon="pencil-square" variant="ghost" class="!px-2" title="Edit" />
                                    <flux:button wire:click="confirmDelete({{ $pelanggan->id }})" size="sm" icon="trash" variant="ghost" class="!px-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <flux:icon.folder-open class="w-8 h-8 opacity-50" />
                                    <p>{{ __('Tidak ada pelanggan ditemukan.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($pelanggans->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $pelanggans->links() }}
            </div>
        @endif
    </div>


    {{-- Create/Edit Modal --}}
    <flux:modal wire:model="showModal" class="md:min-w-[40rem]">
        <div class="mb-4">
            <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                {{ $isEditing ? __('Edit Pelanggan') : __('Tambah Pelanggan') }}
            </h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $isEditing ? __('Update data pelanggan.') : __('Tambah pelanggan baru.') }}
            </p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="nama" label="{{ __('Nama') }}" placeholder="Nama pelanggan" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="email" label="{{ __('Email') }}" type="email" placeholder="email@example.com" />
                <flux:input wire:model="no_hp" label="{{ __('No. HP') }}" placeholder="081234567890" />
            </div>
            
            <flux:textarea wire:model="alamat" label="{{ __('Alamat') }}" placeholder="Alamat lengkap" rows="3" />
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="kota" label="{{ __('Kota') }}" placeholder="Kota" />
                <flux:input wire:model="provinsi" label="{{ __('Provinsi') }}" placeholder="Provinsi" />
                <flux:input wire:model="kode_pos" label="{{ __('Kode Pos') }}" placeholder="10110" />
            </div>
            
            <div>
                <flux:switch wire:model="aktif" label="{{ __('Status Aktif') }}" />
            </div>
            
            <flux:textarea wire:model="catatan" label="{{ __('Catatan') }}" placeholder="Catatan tambahan" rows="2" />

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
                    {{ __('Apakah Anda yakin ingin menghapus pelanggan ini? Tindakan ini tidak dapat dibatalkan.') }}
                </p>
            </div>
            
            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">{{ __('Batal') }}</flux:button>
                <flux:button wire:click="delete" variant="filled" class="bg-red-600 hover:bg-red-700 text-white border-transparent">{{ __('Hapus') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
