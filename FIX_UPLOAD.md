# 🔧 CARA MEMPERBAIKI ERROR UPLOAD VIDEO 10 MB

## ⚠️ MASALAH
Error: `POST Content-Length exceeds the limit of 8388608 bytes (8 MB)`

## ✅ SOLUSI (WAJIB DILAKUKAN)

### Langkah 1: Buka php.ini XAMPP

Buka file php.ini di lokasi berikut:
```
/Applications/XAMPP/xamppfiles/etc/php.ini
```

**Cara cepat:**
1. Buka Finder
2. Tekan `Cmd + Shift + G`
3. Ketik: `/Applications/XAMPP/xamppfiles/etc/`
4. Buka file `php.ini`

### Langkah 2: Cari dan Ubah Konfigurasi

Gunakan `Cmd + F` untuk mencari, lalu ubah nilai berikut:

**Cari: `upload_max_filesize`**
```ini
upload_max_filesize = 12M
```

**Cari: `post_max_size`**
```ini
post_max_size = 12M
```

**Cari: `max_execution_time`**
```ini
max_execution_time = 300
```

**Cari: `max_input_time`**
```ini
max_input_time = 300
```

**Cari: `memory_limit`**
```ini
memory_limit = 256M
```

### Langkah 3: Simpan dan Restart Apache

1. **Simpan** file php.ini (`Cmd + S`)
2. Buka **XAMPP Control Panel**
3. **Stop** Apache
4. **Start** Apache lagi

### Langkah 4: Verifikasi

1. Akses: `http://127.0.0.1:8000/check_php_config.php`
2. Pastikan semua status menunjukkan ✅ OK
3. **HAPUS** file `check_php_config.php` setelah verifikasi

## 📝 CATATAN PENTING

- `post_max_size` **HARUS** lebih besar dari `upload_max_filesize`
- Setelah mengubah php.ini, **WAJIB restart Apache**
- Jika masih error, pastikan Anda mengubah php.ini yang benar (cek dengan `php --ini` di terminal)

## 🚨 TROUBLESHOOTING

Jika masih error setelah mengubah php.ini:

1. **Cek php.ini yang digunakan:**
   ```bash
   php --ini
   ```

2. **Cek konfigurasi saat ini:**
   ```bash
   php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
   ```

3. **Pastikan restart Apache sudah dilakukan**

4. **Cek apakah ada multiple php.ini:**
   - `/Applications/XAMPP/xamppfiles/etc/php.ini` (utama)
   - `/Applications/XAMPP/xamppfiles/etc/php.ini-development`
   - `/Applications/XAMPP/xamppfiles/etc/php.ini-production`

   Pastikan mengubah yang utama!

