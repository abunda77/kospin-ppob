<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-8">
        
        <!-- Welcome Section -->
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                Selamat Datang, {{ auth()->user()->name ?? 'User' }}!
            </h1>
            <p class="text-zinc-500 dark:text-zinc-400 mt-2">
                Kelola semua transaksi pembayaran dan pembelian PPOB Anda di sini.
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Balance Card (Gradient) -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-lime-500 to-emerald-600 text-white shadow-lg shadow-lime-500/20 relative overflow-hidden group">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
                
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-lime-100 font-medium mb-1">Saldo Tersedia</p>
                        <h2 class="text-3xl font-bold">Rp 2.500.000</h2>
                    </div>
                    <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                        <flux:icon name="wallet" class="size-6 text-white" />
                    </div>
                </div>
                <div class="mt-8 flex gap-3 relative z-10">
                    <button class="px-4 py-2 bg-white text-emerald-600 rounded-xl text-sm font-semibold hover:bg-emerald-50 transition-colors shadow-md">
                        + Tambah Saldo
                    </button>
                    <button class="px-4 py-2 bg-emerald-700/50 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700/70 transition-colors border border-white/20">
                        Riwayat
                    </button>
                </div>
            </div>

            <!-- Stats Card 1 -->
            <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400 font-medium mb-1">Transaksi Bulan Ini</p>
                        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">128</h2>
                    </div>
                    <div class="p-2 bg-lime-100 dark:bg-lime-900/30 rounded-xl">
                        <flux:icon name="chart-bar" class="size-6 text-lime-600 dark:text-lime-400" />
                    </div>
                </div>
                 <div class="mt-4 flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400 font-medium bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 rounded-full w-fit">
                    <flux:icon name="arrow-trending-up" class="size-4" />
                    <span>+12.5% dari bulan lalu</span>
                </div>
            </div>

             <!-- Stats Card 2 -->
             <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400 font-medium mb-1">Poin Reward</p>
                        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">4,850</h2>
                    </div>
                    <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                        <flux:icon name="star" class="size-6 text-amber-600 dark:text-amber-400" />
                    </div>
                </div>
                <div class="mt-8">
                     <a href="#" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 flex items-center gap-1 transition-colors">
                        Tukar Poin <flux:icon name="chevron-right" class="size-4" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Access Menu -->
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
            @foreach(['Pulsa', 'Data', 'PLN', 'PDAM', 'BPJS', 'Internet', 'Voucher', 'Lainnya'] as $menu)
            <button class="flex flex-col items-center gap-3 p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-lime-500 dark:hover:border-lime-500 hover:shadow-lg hover:-translate-y-1 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-800 group-hover:bg-lime-50 dark:group-hover:bg-lime-900/30 flex items-center justify-center text-zinc-600 dark:text-zinc-400 group-hover:text-lime-600 dark:group-hover:text-lime-400 transition-colors">
                     <!-- Icon Placeholder (Ideally use dynamic icons) -->
                     <flux:icon name="squares-2x2" class="size-6" />
                </div>
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $menu }}</span>
            </button>
            @endforeach
        </div>

        <!-- Recent Transactions -->
        <div class="rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
             <div class="p-6 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Riwayat Transaksi Terakhir</h3>
                <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-400">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 font-medium uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Tujuan</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach([
                            ['product' => 'Pulsa Telkomsel 100K', 'dest' => '081234567890', 'date' => '18 Jan 2026, 14:30', 'price' => 'Rp 100.500', 'status' => 'Sukses'],
                            ['product' => 'Token PLN 50K', 'dest' => '142345678901', 'date' => '18 Jan 2026, 10:15', 'price' => 'Rp 52.500', 'status' => 'Sukses'],
                            ['product' => 'Paket Data Indosat 25GB', 'dest' => '085812345678', 'date' => '17 Jan 2026, 09:20', 'price' => 'Rp 85.000', 'status' => 'Pending'],
                        ] as $tx)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-white">{{ $tx['product'] }}</td>
                            <td class="px-6 py-4">{{ $tx['dest'] }}</td>
                            <td class="px-6 py-4">{{ $tx['date'] }}</td>
                            <td class="px-6 py-4 font-medium">{{ $tx['price'] }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $tx['status'] === 'Sukses' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $tx['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts::app>
