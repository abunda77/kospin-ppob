# Fitur Export & Import Pelanggan

## 📋 Deskripsi

Fitur ini memungkinkan pengguna untuk:
- **Export** data pelanggan ke format Excel (XLSX) dan PDF
- **Import** data pelanggan dari file Excel (XLSX, XLS) atau CSV
- **Download Template** untuk memudahkan import data

## 📦 Library Yang Digunakan

1. **maatwebsite/excel** (Laravel Excel) v3.1
   - Untuk export dan import file Excel/CSV
   - Dokumentasi: https://docs.laravel-excel.com

2. **barryvdh/laravel-dompdf** v3.1
   - Untuk export file PDF
   - Dokumentasi: https://github.com/barryvdh/laravel-dompdf

## 🚀 Fitur Export

### Export ke Excel (XLSX)
- Format: `.xlsx`
- Kolom: ID, Nama, Email, No. HP, Alamat, Kota, Provinsi, Kode Pos, Status, Catatan, Tanggal Dibuat, Tanggal Diperbarui
- File name: `pelanggan-YYYY-MM-DD-HHMMSS.xlsx`
- **Memory Optimization**: Menggunakan chunking (1000 records per batch)
- **Kapasitas**: Unlimited (dapat handle 100K+ records)
- **Performance**: Optimized untuk data besar

### Export ke PDF
- Format: `.pdf`
- Layout: Tabel dengan styling yang rapi (Landscape A4)
- File name: `pelanggan-YYYY-MM-DD-HHMMSS.pdf`
- Berisi informasi: Total Data di Database, Data Terexport, Tanggal Export
- **Limit**: Maksimal 1000 records (configurable via `.env`)
- **Warning**: Jika data > limit, akan tampil pesan warning
- **Rekomendasi**: Untuk data besar, gunakan Export Excel

### Download Template
- Format: `.xlsx`
- File name: `template-import-pelanggan.xlsx`
- Berisi contoh data dan header yang benar untuk import

## 📥 Fitur Import

### Format File Yang Didukung
- XLSX (Excel)
- XLS (Excel Legacy)
- CSV (Comma Separated Values)

### Ukuran File Maksimal
- 2 MB

### Kolom Yang Diperlukan (Header)

| Kolom | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| Nama | String | Ya | Maksimal 100 karakter |
| Email | String | Tidak | Format email valid, maksimal 100 karakter |
| No. HP | String | Ya | Maksimal 20 karakter |
| Alamat | String | Tidak | - |
| Kota | String | Tidak | Maksimal 100 karakter |
| Provinsi | String | Tidak | Maksimal 100 karakter |
| Kode Pos | String | Tidak | Maksimal 10 karakter |
| Status | String | Tidak | "Aktif" / "Nonaktif" (default: Aktif) |
| Catatan | String | Tidak | - |

### Mode Import

#### 1. Tambah Data (Append / Update Mode)
- **Default mode**
- Data dari file akan **ditambahkan** ke data yang sudah ada
- Jika **No. HP** sudah ada, data lama akan **diupdate** dengan data dari file
- Data lama yang tidak ada di file import akan **tetap dipertahankan**
- Cocok untuk menambah pelanggan baru atau update data massal

#### 2. Timpa Data (Replace Mode)
- Data lama akan **dihapus semua**
- Kemudian diganti dengan data dari file
- ⚠️ **Peringatan**: Proses ini tidak dapat dibatalkan
- Cocok untuk reset database import ulang dari awal

## 💡 Cara Penggunaan

### Export Data

1. Buka halaman **Manajemen Pelanggan**
2. Klik tombol **Export**
3. Pilih format yang diinginkan:
   - **Export Excel** - untuk data dalam format spreadsheet
   - **Export PDF** - untuk data dalam format dokumen PDF
   - **Download Template** - untuk mendapatkan template import

### Import Data

1. **Persiapkan File Import**
   - Download template dengan klik **Download Template**
   - Isi data sesuai format template
   - Pastikan header kolom sesuai

2. **Upload File**
   - Klik tombol **Import**
   - Pilih file Excel/CSV yang sudah disiapkan
   - File akan divalidasi terlebih dahulu

3. **Pilih Mode Import**
   - Modal konfirmasi akan muncul
   - Pilih mode:
     - ✅ **Tambah Data (Append)** - Data ditambahkan
     - ✅ **Timpa Data (Replace)** - Data lama dihapus
   - Klik **Import Sekarang** untuk eksekusi

4. **Selesai**
   - Notifikasi sukses/error akan ditampilkan
   - Refresh halaman untuk melihat data terbaru

## ⚠️ Validasi Import

File import akan divalidasi untuk:
- ✅ Format file (harus XLSX, XLS, atau CSV)
- ✅ Ukuran file (maksimal 2 MB)
- ✅ Kolom wajib (Nama dan No. HP)
- ✅ Format email (jika diisi)
- ✅ Panjang maksimal setiap field

Jika ada error, pesan akan ditampilkan dan import dibatalkan.

## 🔧 Struktur File

```
app/
├── Exports/
│   ├── PelangganExport.php           # Export data ke Excel
│   ├── PelangganTemplateExport.php   # Template import
│
├── Imports/
│   └── PelangganImport.php           # Import data dari Excel/CSV
│
├── Http/
│   └── Controllers/
│       └── PelangganExportController.php  # Controller untuk export
│
├── Livewire/
│   └── PelangganCrud.php             # Component dengan export/import
│
resources/
└── views/
    ├── exports/
    │   └── pelanggan-pdf.blade.php   # Template PDF
    │
    └── livewire/
        └── pelanggan-crud.blade.php  # UI dengan button export/import

routes/
└── web.php                           # Route untuk export

tests/
└── Feature/
    └── PelangganExportImportTest.php # Tests
```

## 🎯 Contoh Format Status

Status pelanggan dapat diisi dengan salah satu nilai berikut:

**Untuk Status Aktif:**
- `Aktif`
- `active`
- `1`
- `yes`
- `ya`
- `true`

**Untuk Status Nonaktif:**
- `Nonaktif`
- `inactive`
- `0`
- `no`
- `tidak`
- `false`

Nilai kosong akan diatur sebagai `Aktif` (default).

## 📝 Catatan Pengembangan

- Import menggunakan **transaction** untuk memastikan data consistency
- Validasi dilakukan sebelum insert data
- Replace mode menggunakan `Pelanggan::query()->delete()` untuk soft delete
- Nama file export menggunakan timestamp untuk menghindari duplikasi
- Loading state otomatis ditampilkan selama proses export/import
- **Export menggunakan Controller terpisah** untuk menghindari error "Malformed UTF-8" dari Livewire (Livewire tidak bisa return binary response)
- Export methods di Livewire component melakukan redirect ke route controller

## 🧪 Testing

Untuk menjalankan test:

```bash
php artisan test --filter=PelangganExportImportTest
```

Test mencakup:
- Component initialization
- Import mode switching (append/replace)
- Validation file import
- Export functionality
- Cancel import

## 🔐 Keamanan

- File upload dibatasi ukuran dan tipe
- Validation dilakukan untuk setiap row data
- User harus authenticated untuk mengakses fitur
- Permission bisa ditambahkan sesuai kebutuhan

## ❓ FAQ & Troubleshooting

### Import Button Tidak Merespon

**Gejala:** Klik tombol Import tidak menampilkan dialog pemilihan file atau modal konfirmasi.

**Kemungkinan Penyebab:**
1. JavaScript Alpine.js tidak terinisialisasi dengan benar
2. Livewire asset cache perlu di-refresh
3. Browser cache yang outdated

**Solusi:**

1. **Clear Cache:**
```bash
php artisan optimize:clear
```

2. **Refresh Browser:**
- Hard refresh: `Ctrl + Shift + R` (Windows/Linux) atau `Cmd + Shift + R` (Mac)
- Clear browser cache

3. **Check Console:**
- Buka browser DevTools (F12)
- Lihat tab Console untuk error JavaScript
- Lihat tab Network untuk request Livewire yang gagal

4. **Verify Alpine.js:**
- Pastikan Alpine.js diload di layout
- Cek di browser console: `typeof Alpine` harus return 'object'

5. **Test File Input Manual:**
```javascript
// Di browser console
document.getElementById('import-file-input').click();
```

### File Upload Stuck di "Mengupload..."

**Penyebab:** File terlalu besar atau koneksi lambat.

**Solusi:**
1. Pastikan file < 2MB
2. Check `upload_max_filesize` di `php.ini`
3. Check network connection

### Modal Import Tidak Muncul Setelah File Dipilih

**Penyebab:** Validation error atau hook `updatedImportFile` tidak terpanggil.

**Solusi:**
1. Check flash message error di halaman
2. Pastikan file format benar (.xlsx, .xls, .csv)
3. Check browser console untuk error

### Error: "The kode_pos field must be a string"

**Gejala:** Saat import, muncul error validasi untuk field `kode_pos` atau `no_hp`.

**Penyebab:** Excel otomatis mengubah angka menjadi tipe numeric, bukan string.

**Solusi:** Sudah diperbaiki di `PelangganImport.php` dengan method `castToString()`.

**Jika Masih Error:**
1. Pastikan kolom kode_pos di Excel tidak diformat sebagai Number
2. Format as Text di Excel sebelum import
3. Atau tambahkan apostrophe di depan: `'10110` → akan jadi string

### Error: "Duplicate entry for key 'no_hp'"

**Penyebab:** No. HP sudah ada di database.

**Solusi:**
1. Gunakan mode "Replace" untuk menimpa semua data
2. Atau hapus/edit data duplicate di file Excel
3. Atau hapus record yang conflict di database

## 📞 Support

Jika ada pertanyaan atau issue, silakan hubungi tim development.

---

**Versi**: 1.0.0  
**Tanggal**: 21 Januari 2026  
**Developer**: Kospin PPOB Development Team
