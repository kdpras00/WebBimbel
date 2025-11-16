# Konfigurasi Upload File Video 10 MB

## Masalah
Error: "POST Content-Length exceeds the limit of 8388608 bytes (8 MB)"

## Solusi

### 1. Update php.ini XAMPP (WAJIB)

Buka file php.ini di XAMPP:
```
/Applications/XAMPP/xamppfiles/etc/php.ini
```

Cari dan ubah nilai berikut:
```ini
upload_max_filesize = 12M
post_max_size = 12M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

### 2. Restart Apache

Setelah mengubah php.ini, restart Apache di XAMPP:
- Buka XAMPP Control Panel
- Stop Apache
- Start Apache

### 3. Verifikasi Konfigurasi

Buat file `info.php` di folder `public`:
```php
<?php
phpinfo();
?>
```

Akses: `http://127.0.0.1:8000/info.php`

Cek nilai:
- `upload_max_filesize` harus 12M
- `post_max_size` harus 12M

**PENTING:** Hapus file `info.php` setelah verifikasi untuk keamanan!

### 4. File yang Sudah Dikonfigurasi

✅ `.htaccess` - sudah dikonfigurasi
✅ `.user.ini` - sudah dibuat
✅ Validasi Laravel - sudah 10 MB

### Catatan
- `post_max_size` harus lebih besar dari `upload_max_filesize`
- Setelah perubahan php.ini, WAJIB restart Apache
- Jika masih error, cek error log Apache

