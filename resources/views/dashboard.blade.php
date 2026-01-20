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
            <!-- Balance Card (Gradient with Glass Effect) -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-lime-400 via-lime-500 to-emerald-600 text-white shadow-xl shadow-lime-500/30 dark:shadow-lime-500/20 relative overflow-hidden group hover:shadow-2xl hover:shadow-lime-500/40 transition-all duration-300">
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-emerald-500/30 rounded-full blur-2xl"></div>
                
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-lime-50 font-medium mb-2 text-sm">Saldo Tersedia</p>
                        <h2 class="text-4xl font-bold tracking-tight">Rp 2.500.000</h2>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm">
                        <flux:icon name="wallet" class="size-7 text-white" />
                    </div>
                </div>
                <div class="mt-8 flex gap-3 relative z-10">
                    <x-button variant="secondary" size="sm" class="bg-white text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 shadow-md">
                        + Tambah Saldo
                    </x-button>
                    <x-button variant="glass" size="sm" class="bg-emerald-700/50 hover:bg-emerald-700/70 border-white/20">
                        Riwayat
                    </x-button>
                </div>
            </div>

            <!-- Stats Card 1 -->
            <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-xl hover:shadow-zinc-200/50 dark:hover:shadow-zinc-900/50 hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400 font-medium mb-2 text-sm">Transaksi Bulan Ini</p>
                        <h2 class="text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">128</h2>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-lime-100 to-lime-200 dark:from-lime-900/40 dark:to-lime-800/30 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <flux:icon name="chart-bar" class="size-7 text-lime-600 dark:text-lime-400" />
                    </div>
                </div>
                 <div class="mt-5 flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50 dark:bg-emerald-900/20 px-3 py-2 rounded-xl w-fit">
                    <flux:icon name="arrow-trending-up" class="size-4" />
                    <span>+12.5% dari bulan lalu</span>
                </div>
            </div>

             <!-- Stats Card 2 -->
             <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-xl hover:shadow-zinc-200/50 dark:hover:shadow-zinc-900/50 hover:-translate-y-1 transition-all duration-300 group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400 font-medium mb-2 text-sm">Poin Reward</p>
                        <h2 class="text-4xl font-bold text-zinc-900 dark:text-white tracking-tight">4,850</h2>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/40 dark:to-amber-800/30 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <flux:icon name="star" class="size-7 text-amber-600 dark:text-amber-400" />
                    </div>
                </div>
                <div class="mt-8">
                     <a href="#" class="text-sm font-semibold text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 flex items-center gap-2 transition-colors group/link">
                        Tukar Poin 
                        <flux:icon name="chevron-right" class="size-4 group-hover/link:translate-x-1 transition-transform" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Access Menu -->
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
            @foreach(['Pulsa', 'Data', 'PLN', 'PDAM', 'BPJS', 'Internet', 'Voucher', 'Lainnya'] as $menu)
            <x-button variant="icon" class="flex-col gap-3 p-4 h-auto hover:border-lime-500 dark:hover:border-lime-500 hover:shadow-lg hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-zinc-50 dark:bg-zinc-800 group-hover:bg-lime-50 dark:group-hover:bg-lime-900/30 flex items-center justify-center text-zinc-600 dark:text-zinc-400 group-hover:text-lime-600 dark:group-hover:text-lime-400 transition-colors">
                     <!-- Icon Placeholder (Ideally use dynamic icons) -->
                     <flux:icon name="squares-2x2" class="size-6" />
                </div>
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $menu }}</span>
            </x-button>
            @endforeach
        </div>

        <!-- Chart Analytics Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Analitik Bisnis PPOB</h2>
                <div class="flex gap-2">
                    <x-button variant="secondary" size="sm" class="text-xs">
                        <flux:icon name="arrow-path" class="size-4" />
                        Refresh Data
                    </x-button>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Transaction Trends Chart -->
                <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Tren Transaksi</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Volume transaksi 7 hari terakhir</p>
                        </div>
                        <div class="p-2 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/30 rounded-xl">
                            <flux:icon name="chart-bar" class="size-5 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="transactionTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Product Category Distribution Chart -->
                <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Distribusi Kategori Produk</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Pembagian transaksi per kategori</p>
                        </div>
                        <div class="p-2 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/40 dark:to-purple-800/30 rounded-xl">
                            <flux:icon name="chart-pie" class="size-5 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Daily Revenue Chart -->
                <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Pendapatan Harian</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Target vs Realisasi minggu ini</p>
                        </div>
                        <div class="p-2 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/40 dark:to-emerald-800/30 rounded-xl">
                            <flux:icon name="currency-dollar" class="size-5 text-emerald-600 dark:text-emerald-400" />
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>

                <!-- Transaction Status Chart -->
                <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Status Transaksi</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Persentase status hari ini</p>
                        </div>
                        <div class="p-2 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/40 dark:to-amber-800/30 rounded-xl">
                            <flux:icon name="clock" class="size-5 text-amber-600 dark:text-amber-400" />
                        </div>
                    </div>
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="transactionStatusChart"></canvas>
                    </div>
                </div>
            </div>
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

    @push('scripts')
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Detect dark mode
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Color configuration
            const colors = {
                primary: isDarkMode ? 'rgba(132, 204, 22, 0.8)' : 'rgba(132, 204, 22, 0.9)',
                primaryLight: isDarkMode ? 'rgba(132, 204, 22, 0.2)' : 'rgba(132, 204, 22, 0.1)',
                grid: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                text: isDarkMode ? 'rgba(255, 255, 255, 0.7)' : 'rgba(0, 0, 0, 0.7)',
            };

            const chartDefaults = {
                plugins: {
                    legend: {
                        labels: {
                            color: colors.text,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: colors.text },
                        grid: { color: colors.grid }
                    },
                    y: {
                        ticks: { color: colors.text },
                        grid: { color: colors.grid }
                    }
                }
            };

            // 1. Transaction Trends Chart (Line Chart)
            const trendsCtx = document.getElementById('transactionTrendsChart');
            if (trendsCtx) {
                new Chart(trendsCtx, {
                    type: 'line',
                    data: {
                        labels: ['14 Jan', '15 Jan', '16 Jan', '17 Jan', '18 Jan', '19 Jan', '20 Jan'],
                        datasets: [{
                            label: 'Total Transaksi',
                            data: [45, 52, 48, 65, 59, 70, 68],
                            borderColor: 'rgb(132, 204, 22)',
                            backgroundColor: 'rgba(132, 204, 22, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgb(132, 204, 22)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                                titleColor: colors.text,
                                bodyColor: colors.text,
                                borderColor: colors.grid,
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' transaksi';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: colors.text },
                                grid: { display: false }
                            },
                            y: {
                                ticks: { 
                                    color: colors.text,
                                    callback: function(value) {
                                        return value;
                                    }
                                },
                                grid: { color: colors.grid }
                            }
                        }
                    }
                });
            }

            // 2. Product Category Distribution (Pie Chart)
            const categoryCtx = document.getElementById('categoryDistributionChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Pulsa & Data', 'PLN', 'PDAM', 'Internet', 'Voucher Game', 'BPJS'],
                        datasets: [{
                            data: [35, 25, 15, 12, 8, 5],
                            backgroundColor: [
                                'rgba(132, 204, 22, 0.8)',   // Lime
                                'rgba(59, 130, 246, 0.8)',   // Blue
                                'rgba(168, 85, 247, 0.8)',   // Purple
                                'rgba(236, 72, 153, 0.8)',   // Pink
                                'rgba(251, 191, 36, 0.8)',   // Amber
                                'rgba(239, 68, 68, 0.8)'     // Red
                            ],
                            borderColor: isDarkMode ? '#18181b' : '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: colors.text,
                                    padding: 15,
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 11
                                    },
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map((label, i) => {
                                                const value = data.datasets[0].data[i];
                                                return {
                                                    text: `${label}: ${value}%`,
                                                    fillStyle: data.datasets[0].backgroundColor[i],
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                                titleColor: colors.text,
                                bodyColor: colors.text,
                                borderColor: colors.grid,
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Daily Revenue Chart (Grouped Bar Chart)
            const revenueCtx = document.getElementById('dailyRevenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Target',
                            data: [5000000, 5000000, 5000000, 5000000, 5000000, 6000000, 6000000],
                            backgroundColor: 'rgba(161, 161, 170, 0.3)',
                            borderColor: 'rgba(161, 161, 170, 0.5)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.7
                        }, {
                            label: 'Realisasi',
                            data: [4500000, 5200000, 4800000, 6100000, 5900000, 6800000, 5500000],
                            backgroundColor: 'rgba(132, 204, 22, 0.8)',
                            borderColor: 'rgb(132, 204, 22)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barPercentage: 0.7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: colors.text,
                                    padding: 15,
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                                titleColor: colors.text,
                                bodyColor: colors.text,
                                borderColor: colors.grid,
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: colors.text },
                                grid: { display: false }
                            },
                            y: {
                                ticks: { 
                                    color: colors.text,
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000) + 'jt';
                                    }
                                },
                                grid: { color: colors.grid }
                            }
                        }
                    }
                });
            }

            // 4. Transaction Status Chart (Doughnut Chart)
            const statusCtx = document.getElementById('transactionStatusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Sukses', 'Pending', 'Gagal'],
                        datasets: [{
                            data: [85, 10, 5],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.8)',    // Green
                                'rgba(251, 191, 36, 0.8)',   // Amber
                                'rgba(239, 68, 68, 0.8)'     // Red
                            ],
                            borderColor: isDarkMode ? '#18181b' : '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: colors.text,
                                    padding: 20,
                                    font: {
                                        family: "'Inter', sans-serif",
                                        size: 12
                                    },
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map((label, i) => {
                                                const value = data.datasets[0].data[i];
                                                return {
                                                    text: `${label}: ${value}%`,
                                                    fillStyle: data.datasets[0].backgroundColor[i],
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: isDarkMode ? 'rgba(24, 24, 27, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                                titleColor: colors.text,
                                bodyColor: colors.text,
                                borderColor: colors.grid,
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush

</x-layouts::app>
