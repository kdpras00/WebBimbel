# 🔄 CARA RESTART APACHE YANG BENAR

## ⚠️ MASALAH
Konfigurasi PHP di php.ini sudah benar, tapi web server masih membaca nilai lama.

## ✅ SOLUSI

### Metode 1: Restart via XAMPP Control Panel (Coba ini dulu)

1. **Buka XAMPP Control Panel**
2. **Klik "Stop" pada Apache** - Tunggu sampai status benar-benar merah/berhenti
3. **Tunggu 5-10 detik** (penting!)
4. **Klik "Start" pada Apache** - Tunggu sampai status hijau/running
5. **Hard refresh browser**: Tekan `Cmd + Shift + R` (Mac) atau `Ctrl + Shift + R` (Windows)
6. **Cek lagi**: `http://127.0.0.1:8000/check_php_config.php`

### Metode 2: Restart via Terminal (Jika Metode 1 tidak bekerja)

```bash
# Stop Apache
sudo /Applications/XAMPP/xamppfiles/bin/httpd -k stop

# Tunggu 5 detik
sleep 5

# Start Apache
sudo /Applications/XAMPP/xamppfiles/bin/httpd -k start
```

### Metode 3: Restart XAMPP Sepenuhnya

1. **Stop semua service** di XAMPP Control Panel (Apache, MySQL)
2. **Tutup XAMPP Control Panel** sepenuhnya
3. **Buka XAMPP Control Panel** lagi
4. **Start Apache**
5. **Hard refresh browser**

### Metode 4: Restart Komputer (Jika semua gagal)

Kadang-kadang perlu restart komputer untuk memastikan semua proses PHP/Apache benar-benar dimatikan.

## 🔍 VERIFIKASI

Setelah restart, akses:
- `http://127.0.0.1:8000/check_php_config.php`
- `http://127.0.0.1:8000/phpinfo_check.php` (untuk detail lengkap)

Pastikan semua nilai menunjukkan:
- ✅ upload_max_filesize: 12M
- ✅ post_max_size: 12M
- ✅ max_execution_time: 300
- ✅ max_input_time: 300
- ✅ memory_limit: 256M

## 🚨 TROUBLESHOOTING

Jika masih tidak berubah:

1. **Cek apakah ada multiple PHP installation:**
   ```bash
   which php
   /Applications/XAMPP/xamppfiles/bin/php --ini
   ```

2. **Cek apakah Apache menggunakan PHP yang benar:**
   - Akses: `http://127.0.0.1:8000/phpinfo_check.php`
   - Lihat "Loaded Configuration File"

3. **Cek error log Apache:**
   ```bash
   tail -f /Applications/XAMPP/xamppfiles/logs/error_log
   ```

4. **Cek apakah ada .htaccess yang override:**
   - File `.htaccess` di `public/.htaccess` mungkin tidak bekerja jika menggunakan PHP-FPM
   - Coba comment sementara bagian PHP configuration di `.htaccess`

5. **Cek apakah menggunakan PHP-FPM:**
   - Jika menggunakan PHP-FPM, perlu restart PHP-FPM juga, bukan hanya Apache

