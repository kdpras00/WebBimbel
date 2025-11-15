<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Kelas;

class KelasSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all siswa
        $siswa = User::where('role', 'siswa')->get();
        
        // Get all kelas
        $kelas = Kelas::all();
        
        if ($siswa->isEmpty() || $kelas->isEmpty()) {
            $this->command->warn('Tidak ada siswa atau kelas. Silakan jalankan seeder lainnya terlebih dahulu.');
            return;
        }
        
        // Jurusan options untuk kelas 10-12
        $jurusanOptions = ['IPA', 'IPS', 'Bahasa', 'Agama'];
        
        // Assign setiap siswa ke beberapa kelas (minimal 1 kelas, maksimal semua kelas)
        foreach ($siswa as $s) {
            // Ambil 1-3 kelas random untuk setiap siswa
            $randomKelas = $kelas->random(rand(1, min(3, $kelas->count())));
            
            foreach ($randomKelas as $k) {
                // Extract kelas number from nama (e.g., "Kelas 10" -> 10)
                $kelasMatch = preg_match('/\d+/', $k->nama, $matches);
                $kelasNumber = $kelasMatch && isset($matches[0]) ? (int)$matches[0] : 0;
                
                // Assign jurusan untuk kelas 10-12, null untuk kelas 1-6
                $jurusan = null;
                if ($kelasNumber >= 10 && $kelasNumber <= 12) {
                    $jurusan = $jurusanOptions[array_rand($jurusanOptions)];
                }
                
                // Insert ke tabel kelas_siswa
                DB::table('kelas_siswa')->updateOrInsert(
                    [
                        'kelas_id' => $k->id,
                        'siswa_id' => $s->id,
                    ],
                    [
                        'jurusan' => $jurusan,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
        
        $this->command->info('Siswa berhasil di-assign ke kelas!');
    }
}
