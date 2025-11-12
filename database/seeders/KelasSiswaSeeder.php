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
        
        // Assign setiap siswa ke beberapa kelas (minimal 1 kelas, maksimal semua kelas)
        foreach ($siswa as $s) {
            // Ambil 1-3 kelas random untuk setiap siswa
            $randomKelas = $kelas->random(rand(1, min(3, $kelas->count())));
            
            foreach ($randomKelas as $k) {
                // Insert ke tabel kelas_siswa
                DB::table('kelas_siswa')->updateOrInsert(
                    [
                        'kelas_id' => $k->id,
                        'siswa_id' => $s->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
        
        $this->command->info('Siswa berhasil di-assign ke kelas!');
    }
}
