# ⚠️ PENTING: RESTART APACHE!

Konfigurasi php.ini sudah benar:
- ✅ `post_max_size=40M`
- ✅ `upload_max_filesize=40M`
- ✅ `memory_limit=512M`

## 🔄 LANGKAH WAJIB:

### 1. RESTART APACHE di XAMPP

**Cara 1: Via XAMPP Control Panel**
1. Buka **XAMPP Control Panel**
2. Klik **Stop** pada Apache
3. Tunggu sampai benar-benar berhenti
4. Klik **Start** pada Apache
5. Pastikan status berubah menjadi hijau/running

**Cara 2: Via Terminal**
```bash
sudo /Applications/XAMPP/xamppfiles/bin/httpd -k stop
sudo /Applications/XAMPP/xamppfiles/bin/httpd -k start
```

### 2. Verifikasi Setelah Restart

Akses: `http://127.0.0.1:8000/check_php_config.php`

Pastikan semua menunjukkan:
- ✅ upload_max_filesize: 40M
- ✅ post_max_size: 40M

### 3. Clear Browser Cache

Setelah restart Apache:
- Tekan `Cmd + Shift + R` (hard refresh)
- Atau clear cache browser

### 4. Coba Upload Lagi

Setelah semua langkah di atas, coba upload video 9 MB lagi.

## 🚨 Jika Masih Error:

1. **Cek apakah php.ini yang benar:**
   ```bash
   php --ini
   ```
   Pastikan "Loaded Configuration File" menunjuk ke `/Applications/XAMPP/xamppfiles/etc/php.ini`

2. **Cek konfigurasi yang aktif:**
   ```bash
   php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
   ```

3. **Pastikan tidak ada file php.ini lain yang override:**
   - Cek di folder project
   - Cek di folder public

4. **Restart ulang XAMPP sepenuhnya:**
   - Stop semua service (Apache, MySQL)
   - Tutup XAMPP Control Panel
   - Buka lagi XAMPP Control Panel
   - Start Apache

