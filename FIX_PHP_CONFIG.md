# 🔧 CARA MEMPERBAIKI KONFIGURASI PHP YANG TIDAK BERUBAH

## ⚠️ MASALAH
File `php.ini` sudah diubah dengan benar, tapi web server masih membaca nilai lama.

## ✅ SOLUSI LENGKAP

### Langkah 1: Verifikasi File php.ini

File php.ini sudah benar di:
```
/Applications/XAMPP/xamppfiles/etc/php.ini
```

Nilai yang sudah benar:
- `upload_max_filesize=12M` (line 798)
- `post_max_size=12M` (line 646)
- `max_execution_time=300` (line 377)
- `max_input_time=300` (line 387)
- `memory_limit=256M` (line 398)

### Langkah 2: Restart Apache dengan Benar

**CARA YANG PALING EFEKTIF:**

1. **Buka XAMPP Control Panel**
2. **Klik "Stop" pada Apache**
3. **TUNGGU** sampai status benar-benar merah/berhenti (bisa 10-30 detik)
4. **TUNGGU LAGI 10 DETIK** setelah Apache berhenti
5. **Klik "Start" pada Apache**
6. **TUNGGU** sampai status hijau/running
7. **Hard refresh browser**: `Cmd + Shift + R` (Mac) atau `Ctrl + Shift + R` (Windows)
8. **Cek lagi**: `http://127.0.0.1:8000/check_php_config.php`

### Langkah 3: Jika Masih Tidak Berubah

**Coba metode ini:**

1. **Stop Apache** di XAMPP Control Panel
2. **Tutup XAMPP Control Panel** sepenuhnya (keluar dari aplikasi)
3. **Buka XAMPP Control Panel** lagi
4. **Start Apache**
5. **Hard refresh browser**

### Langkah 4: Restart via Terminal (Alternatif)

Jika XAMPP Control Panel tidak bekerja:

```bash
# Stop Apache
sudo /Applications/XAMPP/xamppfiles/bin/httpd -k stop

# Tunggu 5 detik
sleep 5

# Start Apache
sudo /Applications/XAMPP/xamppfiles/bin/httpd -k start
```

### Langkah 5: Restart Komputer (Jika Semua Gagal)

Kadang-kadang perlu restart komputer untuk memastikan semua proses PHP/Apache benar-benar dimatikan.

## 🔍 VERIFIKASI

Setelah restart, akses:
- `http://127.0.0.1:8000/check_php_config.php`
- `http://127.0.0.1:8000/phpinfo_check.php`

Pastikan semua nilai menunjukkan:
- ✅ upload_max_filesize: 12M
- ✅ post_max_size: 12M
- ✅ max_execution_time: 300
- ✅ max_input_time: 300
- ✅ memory_limit: 256M

## 🚨 CATATAN PENTING

1. **Jangan lupa hard refresh browser** setelah restart Apache
2. **Tunggu Apache benar-benar berhenti** sebelum start lagi
3. **Browser cache** bisa menyimpan nilai lama - gunakan hard refresh
4. **Jika menggunakan PHP-FPM**, perlu restart PHP-FPM juga, bukan hanya Apache

## 📝 CHECKLIST

- [ ] File php.ini sudah diubah dengan benar
- [ ] Apache sudah di-stop
- [ ] Tunggu 10 detik setelah Apache stop
- [ ] Apache sudah di-start lagi
- [ ] Hard refresh browser (Cmd+Shift+R)
- [ ] Cek `check_php_config.php` - semua harus ✅ OK

