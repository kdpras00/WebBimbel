# Web Bimbel - Sistem Bimbingan Belajar Online

Sistem bimbingan belajar online dengan fitur gamifikasi, role-based access control, dan manajemen pembelajaran lengkap.

## Fitur Utama

### 👨‍💼 Admin
- Mengelola user (pengajar, siswa, wali)
- Mengelola kelas & mata pelajaran
- Mengatur gamifikasi (poin, reward)
- Mengelola jadwal/tutor

### 👨‍🏫 Pengajar
- Upload materi (PDF, video, teks)
- Membuat soal/quiz
- Melihat hasil belajar siswa
- Memberi feedback

### 👨‍🎓 Siswa
- Mengakses materi
- Mengerjakan kuis
- Mendapat poin & reward
- Melihat leaderboard
- Melihat progres belajar

### 👪 Wali
- Melihat nilai anak
- Melihat perkembangan anak

## Teknologi

- **Framework**: Laravel 12
- **UI**: Flowbite UI + Tailwind CSS
- **Database**: MySQL/MariaDB

## Instalasi

1. Clone repository atau extract ke folder web server (XAMPP)
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/WebBimbel
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webbimbel
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi
```bash
php artisan migrate
```

6. Build assets
```bash
npm run build
# atau untuk development
npm run dev
```

7. Jalankan server
```bash
php artisan serve
```

## Struktur Database

- **users**: User dengan role (admin, pengajar, siswa, wali)
- **kelas**: Data kelas
- **mapel**: Mata pelajaran per kelas
- **materi**: Materi pembelajaran
- **quiz**: Data quiz
- **questions**: Soal quiz
- **quiz_results**: Hasil pengerjaan quiz
- **points**: Poin siswa
- **feedback**: Feedback dari pengajar
- **gamification_settings**: Aturan gamifikasi

## Flow Sistem

1. **Login** → Sistem cek role → Redirect ke dashboard sesuai role
2. **Admin** → Setup sistem & kelola data
3. **Pengajar** → Upload materi & buat quiz
4. **Siswa** → Belajar, kerjakan quiz, dapat poin
5. **Wali** → Monitor perkembangan anak

## Gamifikasi

Sistem poin otomatis berdasarkan nilai quiz:
- Nilai > 90 → +20 poin
- Nilai 80-90 → +15 poin
- Nilai < 80 → +10 poin

Aturan dapat dikonfigurasi oleh Admin di halaman Gamifikasi.

## Routes

- `/login` - Halaman login
- `/admin/*` - Routes untuk Admin
- `/pengajar/*` - Routes untuk Pengajar
- `/siswa/*` - Routes untuk Siswa
- `/wali/*` - Routes untuk Wali

## Development

```bash
# Development mode dengan hot reload
npm run dev

# Build untuk production
npm run build
```

## License

MIT License
# WebBimbel
