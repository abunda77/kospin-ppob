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
            
            {{-- Export Buttons (Zinc Theme) --}}
            <flux:dropdown>
                <flux:button class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 shadow-sm transition-all duration-200 group">
                    <flux:icon.arrow-down-tray class="w-4 h-4 text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-700 dark:group-hover:text-zinc-200 mr-2 transition-colors" />
                    <span class="text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-zinc-100">{{ __('Export') }}</span>
                </flux:button>
                <flux:menu>
                    <flux:menu.item wire:click="exportExcel" icon="document-text">
                        {{ __('Export Excel') }}
                    </flux:menu.item>
                    <flux:menu.item wire:click="exportPdf" icon="document">
                        {{ __('Export PDF') }}
                    </flux:menu.item>
                    <flux:menu.separator />
                    <flux:menu.item wire:click="downloadTemplate" icon="arrow-down-circle">
                        {{ __('Download Template') }}
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            {{-- Import Button (Emerald Theme) --}}
            <div x-data="{ uploading: false }" @upload-start.window="uploading = true" @upload-finish.window="uploading = false">
                <div wire:loading.remove wire:target="importFile">
                    <flux:button 
                        @click="$refs.importFileInput.click()" 
                        class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 shadow-sm transition-all duration-200 group"
                    >
                        <flux:icon.arrow-up-tray class="w-4 h-4 text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 mr-2 transition-colors" />
                        <span class="text-emerald-700 dark:text-emerald-400 group-hover:text-emerald-800 dark:group-hover:text-emerald-300">{{ __('Import') }}</span>
                    </flux:button>
                </div>
                <div wire:loading wire:target="importFile">
                    <flux:button disabled class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 opacity-75 cursor-not-allowed">
                        <flux:icon.arrow-path class="animate-spin w-4 h-4 text-emerald-600 dark:text-emerald-400 mr-2" />
                        <span class="text-emerald-700 dark:text-emerald-400">{{ __('Mengupload...') }}</span>
                    </flux:button>
                </div>
                <input 
                    type="file" 
                    wire:model="importFile" 
                    accept=".xlsx,.xls,.csv" 
                    class="hidden"
                    x-ref="importFileInput"
                    id="import-file-input"
                />
            </div>

            {{-- Add Button (Indigo Gradient) --}}
            <flux:button wire:click="create" class="!bg-gradient-to-r !from-indigo-600 !to-violet-600 hover:!from-indigo-500 hover:!to-violet-500 !text-white !border-0 shadow-lg shadow-indigo-500/20 transition-all duration-200 hover:scale-[1.02] whitespace-nowrap">
                <flux:icon.plus class="w-4 h-4 text-white mr-1" />
                <span>{{ __('Add Pelanggan') }}</span>
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
    @if (session()->has('warning'))
        <div class="rounded-md bg-amber-50 p-4 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
            <div class="flex">
                <flux:icon.exclamation-triangle class="h-5 w-5 text-amber-400 mr-2" />
                <div>{{ session('warning') }}</div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm bg-white dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-900 text-white dark:bg-zinc-800 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Nama') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Email') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('No. HP') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Kota') }}
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
                    @forelse ($pelanggans as $pelanggan)
                        <tr wire:key="pelanggan-{{ $pelanggan->id }}" class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-all duration-200">
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
                <flux:switch wire:model="aktif" label="{{ __('Status Aktif') }}" class="data-checked:!bg-gradient-to-r data-checked:!from-emerald-500 data-checked:!to-teal-400 data-checked:!border-0" />
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

    {{-- Import Confirmation Modal --}}
    <flux:modal wire:model="showImportModal" class="md:min-w-[32rem]">
        <div class="mb-4">
            <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                {{ __('Konfirmasi Import Data') }}
            </h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Pilih mode import data pelanggan.') }}
            </p>
        </div>

        <div class="space-y-4">
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-md p-4">
                <div class="flex">
                    <flux:icon.exclamation-triangle class="h-5 w-5 text-amber-400" />
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-300">
                            {{ __('Perhatian') }}
                        </h3>
                        <div class="mt-2 text-sm text-amber-700 dark:text-amber-400">
                            <p>{{ __('Silakan pilih mode import yang sesuai:') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Append Mode --}}
                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors" :class="$wire.importMode === 'append' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500' : ''">
                    <input 
                        type="radio" 
                        wire:model.live="importMode" 
                        name="importMode" 
                        value="append" 
                        class="mt-0.5 h-4 w-4 text-blue-600 border-zinc-300 focus:ring-blue-500"
                    />
                    <div class="flex-1">
                        <div class="font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('Tambah Data (Append)') }}
                        </div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            {{ __('Data dari file akan ditambahkan ke data yang sudah ada. Data lama tetap dipertahankan.') }}
                        </p>
                    </div>
                </label>

                {{-- Replace Mode --}}
                <label class="flex items-start gap-3 cursor-pointer p-3 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors" :class="$wire.importMode === 'replace' ? 'bg-red-50 dark:bg-red-900/20 border-red-500' : ''">
                    <input 
                        type="radio" 
                        wire:model.live="importMode" 
                        name="importMode" 
                        value="replace" 
                        class="mt-0.5 h-4 w-4 text-red-600 border-zinc-300 focus:ring-red-500"
                    />
                    <div class="flex-1">
                        <div class="font-medium text-zinc-900 dark:text-zinc-100">
                            {{ __('Timpa Data (Replace)') }}
                        </div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            {{ __('Data lama akan dihapus semua dan diganti dengan data dari file.') }}
                        </p>
                    </div>
                </label>
            </div>

            @if($importFile)
                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-md p-3">
                    <p class="text-sm text-zinc-700 dark:text-zinc-300">
                        <span class="font-medium">{{ __('File:') }}</span> {{ $importFile->getClientOriginalName() }}
                    </p>
                </div>
            @endif
        </div>


        <div class="flex justify-end gap-2 mt-6">
            <flux:button wire:click="cancelImport" variant="ghost" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </flux:button>
            
            <div wire:loading.remove wire:target="executeImport">
                <flux:button wire:click="executeImport" variant="primary">
                    {{ __('Import Sekarang') }}
                </flux:button>
            </div>
            
            <div wire:loading wire:target="executeImport">
                <flux:button variant="primary" disabled>
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Mengimport...') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
