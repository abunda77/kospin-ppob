<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subKategori = [
            // Sub kategori untuk PPOB (kategori_id = 1)
            ['kategori_id' => 1, 'nama' => 'LISTRIK', 'kode' => 'LISTRIK', 'deskripsi' => 'Token dan tagihan listrik PLN', 'urutan' => 1],
            ['kategori_id' => 1, 'nama' => 'TELEKOMUNIKASI', 'kode' => 'TELEKOMUNIKASI', 'deskripsi' => 'Tagihan telepon dan internet pascabayar', 'urutan' => 2],
            ['kategori_id' => 1, 'nama' => 'MULTIFINANCE', 'kode' => 'MULTIFINANCE', 'deskripsi' => 'Pembayaran cicilan kendaraan dan kredit', 'urutan' => 3],
            ['kategori_id' => 1, 'nama' => 'TV BERBAYAR', 'kode' => 'TV_BERBAYAR', 'deskripsi' => 'Pembayaran TV kabel dan satelit', 'urutan' => 4],
            ['kategori_id' => 1, 'nama' => 'Nexparabola', 'kode' => 'NEXPARABOLA', 'deskripsi' => 'Pembayaran Nexmedia/Parabola', 'urutan' => 5],
            ['kategori_id' => 1, 'nama' => 'PDAM', 'kode' => 'PDAM', 'deskripsi' => 'Pembayaran tagihan air PDAM', 'urutan' => 6],
            ['kategori_id' => 1, 'nama' => 'ASURANSI', 'kode' => 'ASURANSI', 'deskripsi' => 'Pembayaran premi asuransi', 'urutan' => 7],
            ['kategori_id' => 1, 'nama' => 'TRANSFER DANA', 'kode' => 'TRANSFER_DANA', 'deskripsi' => 'Transfer dana antar bank dan e-wallet', 'urutan' => 8],
            ['kategori_id' => 1, 'nama' => 'PGN', 'kode' => 'PGN', 'deskripsi' => 'Pembayaran tagihan gas PGN', 'urutan' => 9],
            ['kategori_id' => 1, 'nama' => 'VOUCHER', 'kode' => 'VOUCHER', 'deskripsi' => 'Voucher belanja dan gift card', 'urutan' => 10],
            ['kategori_id' => 1, 'nama' => 'STREAMING', 'kode' => 'STREAMING', 'deskripsi' => 'Langganan platform streaming (Netflix, Spotify, dll)', 'urutan' => 11],
            ['kategori_id' => 1, 'nama' => 'DIRECT TOPUP', 'kode' => 'DIRECT_TOPUP', 'deskripsi' => 'Top up saldo langsung ke akun', 'urutan' => 12],
            ['kategori_id' => 1, 'nama' => 'UANG ELEKTRONIK', 'kode' => 'UANG_ELEKTRONIK', 'deskripsi' => 'Top up e-money dan e-wallet', 'urutan' => 13],
            ['kategori_id' => 1, 'nama' => 'PAJAK', 'kode' => 'PAJAK', 'deskripsi' => 'Pembayaran pajak (PBB, kendaraan, dll)', 'urutan' => 14],

            // Sub kategori untuk Game (kategori_id = 2)
            ['kategori_id' => 2, 'nama' => 'PUBG Mobile', 'kode' => 'PUBGM', 'deskripsi' => 'UC PUBG Mobile', 'urutan' => 1],
            ['kategori_id' => 2, 'nama' => 'FREE FIRE', 'kode' => 'FF', 'deskripsi' => 'Diamond Free Fire', 'urutan' => 2],
            ['kategori_id' => 2, 'nama' => 'MOBILE LEGEND', 'kode' => 'ML', 'deskripsi' => 'Diamond Mobile Legends Bang Bang', 'urutan' => 3],
            ['kategori_id' => 2, 'nama' => 'ROBLOX', 'kode' => 'ROBLOX', 'deskripsi' => 'Robux Roblox', 'urutan' => 4],
            ['kategori_id' => 2, 'nama' => 'STEAM WALLET', 'kode' => 'STEAM', 'deskripsi' => 'Steam Wallet Code', 'urutan' => 5],
            ['kategori_id' => 2, 'nama' => 'Magic Chess: Go Go', 'kode' => 'MAGIC_CHESS', 'deskripsi' => 'Diamond Magic Chess', 'urutan' => 6],
            ['kategori_id' => 2, 'nama' => 'VALORANT', 'kode' => 'VALORANT', 'deskripsi' => 'Valorant Points (VP)', 'urutan' => 7],
            ['kategori_id' => 2, 'nama' => 'POINT BLANK BEYOND LIMITS', 'kode' => 'PBBL', 'deskripsi' => 'Cash Point Blank Beyond Limits', 'urutan' => 8],
            ['kategori_id' => 2, 'nama' => 'League of Legends : PC', 'kode' => 'LOL_PC', 'deskripsi' => 'Riot Points League of Legends PC', 'urutan' => 9],
            ['kategori_id' => 2, 'nama' => 'League of Legends: Wild Rift', 'kode' => 'LOL_WR', 'deskripsi' => 'Wild Cores League of Legends Wild Rift', 'urutan' => 10],
            ['kategori_id' => 2, 'nama' => 'EA SPORTS FC MOBILE', 'kode' => 'EA_FC', 'deskripsi' => 'FC Points EA Sports FC Mobile', 'urutan' => 11],
            ['kategori_id' => 2, 'nama' => 'Voucher Fortnite V Bucks', 'kode' => 'FORTNITE', 'deskripsi' => 'V-Bucks Fortnite', 'urutan' => 12],
            ['kategori_id' => 2, 'nama' => 'GARENAONLINE', 'kode' => 'GARENA', 'deskripsi' => 'Garena Shells', 'urutan' => 13],
            ['kategori_id' => 2, 'nama' => 'Delta Force (Garena)', 'kode' => 'DELTA_FORCE', 'deskripsi' => 'Combat Cash Delta Force', 'urutan' => 14],
            ['kategori_id' => 2, 'nama' => 'Garena Undawn', 'kode' => 'UNDAWN', 'deskripsi' => 'RC Garena Undawn', 'urutan' => 15],
            ['kategori_id' => 2, 'nama' => 'Call of Duty Mobile', 'kode' => 'CODM', 'deskripsi' => 'CP Call of Duty Mobile', 'urutan' => 16],
            ['kategori_id' => 2, 'nama' => 'Genshin Impact', 'kode' => 'GENSHIN', 'deskripsi' => 'Genesis Crystal Genshin Impact', 'urutan' => 17],
            ['kategori_id' => 2, 'nama' => 'Honor of Kings', 'kode' => 'HOK', 'deskripsi' => 'Tokens Honor of Kings', 'urutan' => 18],
            ['kategori_id' => 2, 'nama' => 'Honkai Star Rail UID', 'kode' => 'HSR', 'deskripsi' => 'Oneiric Shard Honkai Star Rail', 'urutan' => 19],
            ['kategori_id' => 2, 'nama' => 'Yalla Ludo Diamonds', 'kode' => 'YALLA_LUDO', 'deskripsi' => 'Diamonds Yalla Ludo', 'urutan' => 20],
            ['kategori_id' => 2, 'nama' => 'Voucher PlayStation Network (PSN)', 'kode' => 'PSN', 'deskripsi' => 'PlayStation Network Card', 'urutan' => 21],
            ['kategori_id' => 2, 'nama' => 'Voucher Nintendo', 'kode' => 'NINTENDO', 'deskripsi' => 'Nintendo eShop Card', 'urutan' => 22],
            ['kategori_id' => 2, 'nama' => 'Voucher Megaxus', 'kode' => 'MEGAXUS', 'deskripsi' => 'MI Cash Megaxus', 'urutan' => 23],
            ['kategori_id' => 2, 'nama' => 'Voucher Blizzard Battle Net', 'kode' => 'BLIZZARD', 'deskripsi' => 'Battle.net Balance', 'urutan' => 24],

            // Sub kategori untuk Prabayar (kategori_id = 3)
            ['kategori_id' => 3, 'nama' => 'Telkomsel', 'kode' => 'TSEL', 'deskripsi' => 'Pulsa Telkomsel / Simpati / AS / Loop', 'urutan' => 1],
            ['kategori_id' => 3, 'nama' => 'Indosat', 'kode' => 'ISAT', 'deskripsi' => 'Pulsa Indosat Ooredoo / IM3', 'urutan' => 2],
            ['kategori_id' => 3, 'nama' => 'XL Axiata', 'kode' => 'XL', 'deskripsi' => 'Pulsa XL / AXIS', 'urutan' => 3],
            ['kategori_id' => 3, 'nama' => 'Tri', 'kode' => 'TRI', 'deskripsi' => 'Pulsa 3 (Tri)', 'urutan' => 4],
            ['kategori_id' => 3, 'nama' => 'Smartfren', 'kode' => 'SMART', 'deskripsi' => 'Pulsa Smartfren', 'urutan' => 5],
            ['kategori_id' => 3, 'nama' => 'By.U', 'kode' => 'BYU', 'deskripsi' => 'Pulsa By.U', 'urutan' => 6],

            // Sub kategori untuk Paket Data (kategori_id = 4)
            ['kategori_id' => 4, 'nama' => 'Telkomsel Data', 'kode' => 'TSEL_DATA', 'deskripsi' => 'Paket data internet Telkomsel', 'urutan' => 1],
            ['kategori_id' => 4, 'nama' => 'Indosat Data', 'kode' => 'ISAT_DATA', 'deskripsi' => 'Paket data internet Indosat', 'urutan' => 2],
            ['kategori_id' => 4, 'nama' => 'XL Data', 'kode' => 'XL_DATA', 'deskripsi' => 'Paket data internet XL Axiata', 'urutan' => 3],
            ['kategori_id' => 4, 'nama' => 'Tri Data', 'kode' => 'TRI_DATA', 'deskripsi' => 'Paket data internet Tri', 'urutan' => 4],
            ['kategori_id' => 4, 'nama' => 'Smartfren Data', 'kode' => 'SMART_DATA', 'deskripsi' => 'Paket data internet Smartfren', 'urutan' => 5],
            ['kategori_id' => 4, 'nama' => 'Axis Data', 'kode' => 'AXIS_DATA', 'deskripsi' => 'Paket data internet Axis', 'urutan' => 6],
            ['kategori_id' => 4, 'nama' => 'By.U Data', 'kode' => 'BYU_DATA', 'deskripsi' => 'Paket data internet By.U', 'urutan' => 7],
        ];

        foreach ($subKategori as $item) {
            \App\Models\SubKategori::create($item);
        }
    }
}
