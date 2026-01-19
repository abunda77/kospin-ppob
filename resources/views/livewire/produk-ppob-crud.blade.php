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
            <flux:button wire:click="create" variant="primary" icon="plus">
                {{ __('Tambah Produk') }}
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
    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Kode') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Nama Produk') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Sub Kategori') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('HPP') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Harga Beli') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Harga Jual') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Profit') }}
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
                    @forelse ($produks as $produk)
                        <tr wire:key="produk-{{ $produk->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
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
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    Rp {{ number_format($produk->hpp, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm text-zinc-900 dark:text-zinc-100">
                                    Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium {{ $produk->profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    Rp {{ number_format($produk->profit, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($produk->aktif)
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
</div>
