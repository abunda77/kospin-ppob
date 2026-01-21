# Troubleshooting Export & Import Pelanggan

## ❌ Error Export PDF: "Malformed UTF-8 characters"

### Masalah
```
InvalidArgumentException
vendor\laravel\framework\src\Illuminate\Http\JsonResponse.php:89
Malformed UTF-8 characters, possibly incorrectly encoded
```

### Penyebab
Livewire component tidak bisa mengembalikan binary file response (PDF/Excel) secara langsung. Livewire mengharapkan JSON response, bukan file download.

### Solusi ✅
Export sekarang menggunakan **controller terpisah** (`PelangganExportController`):

1. **Livewire component** (`PelangganCrud.php`) melakukan **redirect** ke route
2. **Controller** (`PelangganExportController.php`) mengembalikan file download

```php
// Di Livewire Component
public function exportPdf()
{
    return redirect()->route('pelanggan.export.pdf');
}

// Di Controller
public function exportPdf()
{
    $pelanggans = Pelanggan::orderBy('nama')->get();
    $pdf = Pdf::loadView('exports.pelanggan-pdf', compact('pelanggans'));
    return $pdf->download('pelanggan-' . now()->format('Y-m-d-His') . '.pdf');
}
```

### Routes
```php
Route::get('/pelanggan/export/excel', [PelangganExportController::class, 'exportExcel'])
    ->name('pelanggan.export.excel');
Route::get('/pelanggan/export/pdf', [PelangganExportController::class, 'exportPdf'])
    ->name('pelanggan.export.pdf');
Route::get('/pelanggan/export/template', [PelangganExportController::class, 'downloadTemplate'])
    ->name('pelanggan.export.template');
```

---

## ❌ Error Import: "File tidak ditemukan"

### Masalah
File berhasil diupload tapi import gagal dengan pesan "File tidak ditemukan"

### Solusi
1. Pastikan `storage/app` memiliki permission write
2. Run: `php artisan storage:link`
3. Check disk configuration di `config/filesystems.php`

---

## ❌ Error Import: "Nama harus diisi"

### Masalah
Error validasi muncul saat import meskipun kolom Nama sudah diisi

### Penyebab
- Header kolom tidak sesuai dengan yang diharapkan
- Ada spasi atau karakter tersembunyi di header

### Solusi
1. Download template resmi dengan klik **Download Template**
2. Copy paste data ke template tersebut
3. Pastikan header PERSIS seperti ini:
   ```
   Nama,Email,No. HP,Alamat,Kota,Provinsi,Kode Pos,Status,Catatan
   ```

---

## ❌ Error Import: "Email sudah digunakan"

### Masalah
Ada email yang duplikat dalam database atau file import

### Solusi
1. **Mode Append**: Pastikan email di file berbeda dengan yang sudah ada di database
2. **Mode Replace**: Pastikan tidak ada email duplikat DALAM file import itu sendiri
3. Cek dengan query:
   ```sql
   SELECT email, COUNT(*) 
   FROM pelanggan 
   GROUP BY email 
   HAVING COUNT(*) > 1;
   ```

---

## ❌ Error: "Maximum execution time exceeded"

### Masalah
Import file besar melebihi time limit PHP

### Solusi
1. Pecah file menjadi beberapa bagian (max 1000-2000 rows per file)
2. Atau tingkatkan `max_execution_time` di `php.ini`:
   ```ini
   max_execution_time = 300
   ```

---

## ❌ Error: "Memory limit exceeded"

### Masalah
Import file besar melebihi memory limit PHP

### Solusi
1. Tingkatkan `memory_limit` di `php.ini`:
   ```ini
   memory_limit = 512M
   ```
2. Atau pecah file menjadi lebih kecil

---

## ❌ File Excel Tidak Bisa Dibuka

### Masalah
File hasil export tidak bisa dibuka di Excel

### Penyebab
1. Encoding issue
2. Data mengandung karakter khusus

### Solusi
1. Buka file dengan LibreOffice/Google Sheets terlebih dahulu
2. Save as dengan encoding UTF-8
3. Pastikan tidak ada karakter aneh di data

---

## ❌ PDF Kosong atau Format Rusak

### Masalah
Export PDF berhasil tapi isinya kosong atau format tidak rapi

### Solusi
1. Check apakah ada data di database:
   ```bash
   php artisan tinker
   >>> App\Models\Pelanggan::count()
   ```
2. Check template Blade di `resources/views/exports/pelanggan-pdf.blade.php`
3. Pastikan DomPDF sudah terinstall:
   ```bash
   composer show barryvdh/laravel-dompdf
   ```

---

## 🔍 Debugging Tips

### 1. Check Logs
```bash
tail -f storage/logs/laravel.log
```

### 2. Enable Debug Mode
Di `.env`:
```env
APP_DEBUG=true
```

### 3. Test Import dengan Data Minimal
Buat file test dengan 1-2 baris saja untuk isolasi masalah

### 4. Check Database Connection
```bash
php artisan db:show
```

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 📞 Masih Error?

Jika masalah masih berlanjut:

1. **Cek Laravel logs**: `storage/logs/laravel.log`
2. **Check browser console**: F12 → Console tab
3. **Test dengan data sample**: Gunakan template yang sudah disediakan
4. **Contact support**: Kirim error log lengkap ke tim development

---

**Last Updated**: 21 Januari 2026  
**Version**: 1.0.1
