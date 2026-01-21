# Contoh Data Import Pelanggan

## Format CSV

Simpan file ini sebagai `.csv` dengan encoding UTF-8:

```csv
Nama,Email,No. HP,Alamat,Kota,Provinsi,Kode Pos,Status,Catatan
"John Doe","john@example.com","081234567890","Jl. Contoh No. 123","Jakarta","DKI Jakarta","10110","Aktif","Pelanggan VIP"
"Jane Smith","jane@example.com","081298765432","Jl. Sample No. 456","Bandung","Jawa Barat","40123","Aktif",""
"Ahmad Yani",,"082156789012","Jl. Merdeka No. 789","Surabaya","Jawa Timur","60123","Aktif",""
"Siti Aminah","siti@example.com","081345678901","Jl. Veteran No. 321","Yogyakarta","DI Yogyakarta","55123","Nonaktif","Pelanggan tidak aktif"
```

## Penjelasan Kolom

### 1. Nama (Wajib)
- Maksimal 100 karakter
- Contoh: "John Doe", "PT. ABC Indonesia"

### 2. Email (Opsional)
- Harus format email yang valid
- Maksimal 100 karakter
- Contoh: "john@example.com"
- Boleh dikosongkan

### 3. No. HP (Wajib)
- Maksimal 20 karakter
- Boleh menggunakan format apapun
- Contoh: "081234567890", "+62-812-3456-7890"

### 4. Alamat (Opsional)
- Alamat lengkap pelanggan
- Contoh: "Jl. Sudirman No. 123, RT 01/RW 02"

### 5. Kota (Opsional)
- Maksimal 100 karakter
- Contoh: "Jakarta", "Bandung"

### 6. Provinsi (Opsional)
- Maksimal 100 karakter
- Contoh: "DKI Jakarta", "Jawa Barat"

### 7. Kode Pos (Opsional)
- Maksimal 10 karakter
- Contoh: "10110", "40123"

### 8. Status (Opsional)
- Nilai valid:
  - **Aktif**: Aktif, active, 1, yes, ya, true
  - **Nonaktif**: Nonaktif, inactive, 0, no, tidak, false
- Default: Aktif (jika dikosongkan)

### 9. Catatan (Opsional)
- Catatan tambahan tentang pelanggan
- Contoh: "Pelanggan VIP", "Baru daftar"

## Tips Import

### ✅ DO (Lakukan)
- Download template terlebih dahulu
- Pastikan header kolom sesuai
- Isi kolom wajib (Nama dan No. HP)
- Gunakan format email yang valid
- Simpan file dengan encoding UTF-8

### ❌ DON'T (Jangan)
- Mengubah nama header kolom
- Menggunakan karakter khusus berlebihan
- File lebih dari 2 MB
- Mengosongkan kolom Nama atau No. HP
- Menggunakan format file selain XLSX, XLS, CSV

## Contoh Import Berhasil

**Mode Append (Tambah Data)**
```
Sebelum: 100 pelanggan
Setelah: 100 + 50 = 150 pelanggan
```

**Mode Replace (Timpa Data)**
```
Sebelum: 100 pelanggan
Setelah: 50 pelanggan (hanya dari file import)
```

## Troubleshooting

### Error: "File harus berformat XLSX, XLS, atau CSV"
**Solusi**: Pastikan ekstensi file benar (.xlsx, .xls, atau .csv)

### Error: "Ukuran file maksimal 2MB"
**Solusi**: Pecah data menjadi beberapa file atau hapus kolom yang tidak perlu

### Error: "Nama harus diisi"
**Solusi**: Pastikan setiap baris memiliki nilai di kolom Nama

### Error: "No. HP harus diisi"
**Solusi**: Pastikan setiap baris memiliki nilai di kolom No. HP

### Error: "Email tidak valid"
**Solusi**: Periksa format email (harus ada @ dan domain)

### Error: "Email sudah digunakan"
**Solusi**: Hapus baris dengan email yang duplikat

### Error: "No. HP sudah digunakan"
**Solusi**: Hapus baris dengan nomor HP yang duplikat

## Template Download

Untuk kemudahan, gunakan fitur **Download Template** di halaman Manajemen Pelanggan untuk mendapatkan file Excel dengan format yang sudah benar dan contoh data.
