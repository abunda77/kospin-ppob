<div class="space-y-6">
    {{-- Header & Controls --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
            {{ __('Manajemen Produk PPOB') }}
        </h2>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Cari produk..." 
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

            <flux:button wire:click="create" class="!bg-gradient-to-r !from-indigo-600 !to-violet-600 hover:!from-indigo-500 hover:!to-violet-500 !text-white !border-0 shadow-lg shadow-indigo-500/20 transition-all duration-200 hover:scale-[1.02] whitespace-nowrap">
                <flux:icon.plus class="w-4 h-4 text-white mr-1" />
                <span>{{ __('Tambah Produk') }}</span>
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

    {{-- Filter by Sub Kategori --}}
    <div class="flex gap-2 items-center">
        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Filter Sub Kategori:') }}</label>
        <flux:select wire:model.live="filterSubKategoriId" placeholder="Semua Sub Kategori" class="w-64">
            <option value="">{{ __('Semua Sub Kategori') }}</option>
            @foreach($subKategoris as $subKategori)
                <option value="{{ $subKategori->id }}">
                    {{ $subKategori->kategori->nama }} - {{ $subKategori->nama }}
                </option>
            @endforeach
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm bg-white dark:bg-zinc-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-900 text-white dark:bg-zinc-800 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Kode') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Nama Produk') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            {{ __('Sub Kategori') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            {{ __('HPP') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            {{ __('Harga Beli') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            {{ __('Harga Jual') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            {{ __('Profit') }}
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
                    @forelse ($produks as $produk)
                        <tr wire:key="produk-{{ $produk->id }}" class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10 dark:bg-zinc-400/10 dark:text-zinc-400 dark:ring-zinc-400/20">
                                    {{ $produk->kode }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $produk->nama_produk }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $produk->subKategori->kategori->nama }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $produk->subKategori->nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-rose-600 dark:text-rose-400 font-mono">
                                    Rp {{ number_format($produk->hpp, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-amber-600 dark:text-amber-400 font-mono">
                                    Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400 font-mono">
                                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium font-mono {{ $produk->profit >= 0 ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20' }}">
                                    Rp {{ number_format($produk->profit, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($produk->aktif)
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
                                    <flux:button wire:click="edit({{ $produk->id }})" size="sm" icon="pencil-square" variant="ghost" class="!px-2" title="Edit" />
                                    <flux:button wire:click="confirmDelete({{ $produk->id }})" size="sm" icon="trash" variant="ghost" class="!px-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <flux:icon.folder-open class="w-8 h-8 opacity-50" />
                                    <p>{{ __('Tidak ada produk ditemukan.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($produks->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $produks->links() }}
            </div>
        @endif
    </div>


    {{-- Create/Edit Modal --}}
    <flux:modal wire:model="showModal" class="md:min-w-[40rem]">
        <div class="mb-4">
            <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                {{ $isEditing ? __('Edit Produk PPOB') : __('Tambah Produk PPOB') }}
            </h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $isEditing ? __('Update data produk PPOB.') : __('Tambah produk PPOB baru.') }}
            </p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="kode" label="{{ __('Kode Produk') }}" placeholder="PLN5K" />
                <flux:select wire:model="sub_kategori_id" label="{{ __('Sub Kategori') }}" placeholder="Pilih Sub Kategori">
                    @foreach($subKategoris as $subKategori)
                        <option value="{{ $subKategori->id }}">
                            {{ $subKategori->kategori->nama }} - {{ $subKategori->nama }}
                        </option>
                    @endforeach
                </flux:select>
            </div>
            
            <flux:input wire:model="nama_produk" label="{{ __('Nama Produk') }}" placeholder="Nama produk" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model.live="hpp" label="{{ __('HPP') }}" type="number" step="0.01" min="0" placeholder="0" />
                <flux:input wire:model.live="biaya_admin" label="{{ __('Biaya Admin') }}" type="number" step="0.01" min="0" placeholder="0" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model.live="fee_mitra" label="{{ __('Fee Mitra') }}" type="number" step="0.01" min="0" placeholder="0" />
                <flux:input wire:model.live="markup" label="{{ __('Markup') }}" type="number" step="0.01" min="0" placeholder="0" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="harga_beli" label="{{ __('Harga Beli') }}" type="number" step="0.01" min="0" placeholder="0" readonly class="bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed" />
                <flux:input wire:model="harga_jual" label="{{ __('Harga Jual') }}" type="number" step="0.01" min="0" placeholder="0" readonly class="bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed" />
                <flux:input wire:model="profit" label="{{ __('Profit') }}" type="number" step="0.01" placeholder="0" readonly class="bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed" />
            </div>

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
                    {{ __('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.') }}
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
                {{ __('Pilih mode import data produk.') }}
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
