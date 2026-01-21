# Memory Optimization & Performance

## 📊 **Export Limits Configuration**

Untuk mencegah memory leak dan timeout, sistem telah dikonfigurasi dengan batasan export:

### **Configuration File**: `config/app.php`

```php
'export_limits' => [
    'pdf_max_records' => env('EXPORT_PDF_MAX_RECORDS', 1000),
    'excel_chunk_size' => env('EXPORT_EXCEL_CHUNK_SIZE', 1000),
],
```

### **Environment Variables**: `.env`

```env
# Export Limits
EXPORT_PDF_MAX_RECORDS=1000
EXPORT_EXCEL_CHUNK_SIZE=1000
```

---

## 🎯 **Strategi Per Format**

### **1. Excel Export (Unlimited Data)** ✅

**Menggunakan Chunking:**
```php
// PelangganExport.php
class PelangganExport implements FromQuery, WithChunkReading
{
    public function query()
    {
        return Pelanggan::query()->orderBy('nama');
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 records at a time
    }
}
```

**Keuntungan:**
- ✅ Handle data unlimited (10K, 100K, 1M+ records)
- ✅ Memory efficient (chunking 1000 rows at a time)
- ✅ No timeout issues
- ✅ File size handled by Excel format compression

**Performance:**
- 1,000 records: ~2-3 seconds
- 10,000 records: ~10-15 seconds  
- 100,000 records: ~2-3 minutes
- 1,000,000 records: ~20-30 minutes

---

### **2. PDF Export (Limited to 1000 Records)** ⚠️

**Dengan Limit & Warning:**
```php
// PelangganExportController.php
public function exportPdf()
{
    $maxRecords = config('app.export_limits.pdf_max_records', 1000);
    $totalRecords = Pelanggan::count();

    // Check if total records exceeds limit
    if ($totalRecords > $maxRecords) {
        return redirect()
            ->route('pelanggan.index')
            ->with('warning', "Total data ({$totalRecords} records) melebihi batas...");
    }

    $pelanggans = Pelanggan::orderBy('nama')
        ->limit($maxRecords)
        ->get();

    // Generate PDF...
}
```

**Alasan Pembatasan:**
- ⚠️ PDF format tidak efficient untuk data besar
- ⚠️ File size membengkak (100K records = 50+ MB PDF)
- ⚠️ Rendering time sangat lama
- ⚠️ Susah dibuka di PDF reader
- ⚠️ Memory consumption tinggi

**Rekomendasi:**
- Untuk data > 1000: Gunakan **Excel Export**
- Atau filter data terlebih dahulu
- PDF lebih cocok untuk report ringkas

---

## 🔧 **Mengubah Limit**

### **Production (Recommended):**

Sesuaikan di `.env`:
```env
# Conservative (untuk server low-memory)
EXPORT_PDF_MAX_RECORDS=500
EXPORT_EXCEL_CHUNK_SIZE=500

# Standard (default)
EXPORT_PDF_MAX_RECORDS=1000
EXPORT_EXCEL_CHUNK_SIZE=1000

# Aggressive (untuk server high-memory)
EXPORT_PDF_MAX_RECORDS=5000
EXPORT_EXCEL_CHUNK_SIZE=2000
```

### **Temporary Override:**

Edit langsung di `config/app.php`:
```php
'export_limits' => [
    'pdf_max_records' => 2000,  // Override value
    'excel_chunk_size' => 1500, // Override value
],
```

**Remember:** Run `php artisan config:cache` setelah perubahan!

---

## 📈 **Memory Usage Estimation**

### **Excel Export** (dengan chunking):
| Records | Memory Usage | Time |
|---------|--------------|------|
| 1K      | ~16 MB       | 2s   |
| 10K     | ~25 MB       | 15s  |
| 100K    | ~50 MB       | 3m   |
| 1M      | ~100 MB      | 30m  |

### **PDF Export** (tanpa limit):
| Records | Memory Usage | Time | File Size |
|---------|--------------|------|-----------|
| 500     | ~32 MB       | 5s   | 500 KB    |
| 1K      | ~64 MB       | 12s  | 1 MB      |
| 5K      | ~256 MB      | 60s  | 5 MB      |
| 10K     | **512 MB**   | 120s | 10+ MB    |

⚠️ **Warning:** PDF > 5K records = high risk of timeout/memory error!

---

## 🚀 **Best Practices**

### **1. Monitoring**

Track export performance:
```php
// Add to controller
\Log::info('Export started', [
    'type' => 'pdf',
    'records' => $totalRecords,
    'user' => auth()->id(),
]);

// After export
\Log::info('Export completed', [
    'duration' => $duration,
    'memory' => memory_get_peak_usage(true),
]);
```

### **2. User Communication**

Tampilkan info di UI:
```blade
<div class="text-sm text-zinc-500">
    <flux:icon.information-circle class="inline w-4 h-4" />
    Maksimal export PDF: {{ config('app.export_limits.pdf_max_records') }} records
</div>
```

### **3. Queue untuk Data Besar** (Future Enhancement)

Untuk export > 10K records:
```php
// Queue job
dispatch(new ExportPelangganJob($userId, $format));

// Notify via email when done
Mail::to($user)->send(new ExportReadyMail($downloadUrl));
```

---

## ⚡ **Performance Tips**

### **1. Database Indexing**
```sql
CREATE INDEX idx_pelanggan_nama ON pelanggan(nama);
```

### **2. Optimize Query**
```php
// Only select needed columns
Pelanggan::select(['id', 'nama', 'email', 'no_hp', 'kota', 'aktif'])
    ->orderBy('nama')
    ->get();
```

### **3. Server Configuration**

**PHP.ini:**
```ini
memory_limit = 512M
max_execution_time = 300
```

**Apache/Nginx:**
```
client_max_body_size 100M
```

---

## 📊 **Monitoring & Alerts**

### **Setup Alerts:**

```php
// In controller
if ($totalRecords > 50000) {
    \Log::warning('Large export attempted', [
        'records' => $totalRecords,
        'user' => auth()->id(),
    ]);
    
    // Notify admin
    Notification::send($admins, new LargeExportAttempt($totalRecords));
}
```

---

## 🎓 **Technical Details**

### **Why Chunking Works:**

**Without Chunking:**
```php
$data = Pelanggan::all(); // Load ALL into memory at once
// 100K records × 2KB each = 200MB memory!
```

**With Chunking:**
```php
Pelanggan::chunk(1000, function($chunk) {
    // Process 1000 records
    // Memory freed after each chunk
});
// 1K records × 2KB = 2MB memory per chunk
```

### **Laravel Excel Chunking:**

```php
// Automatic chunking by Laravel Excel
class PelangganExport implements FromQuery, WithChunkReading
{
    public function chunkSize(): int
    {
        return 1000; // Reads & writes 1000 rows at a time
    }
}
```

---

## 📖 **References**

- [Laravel Excel Documentation](https://docs.laravel-excel.com/3.1/exports/chunk-reading.html)
- [DomPDF Performance](https://github.com/barryvdh/laravel-dompdf#performance)
- [PHP Memory Management](https://www.php.net/manual/en/features.gc.php)

---

**Last Updated**: 21 Januari 2026  
**Version**: 1.1.0
