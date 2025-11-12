<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;

class KelasPengajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all pengajar
        $pengajar = User::where('role', 'pengajar')->get();
        
        // Get all kelas
        $kelas = Kelas::all();
        
        // Get all mapel
        $mapel = Mapel::all();
        
        if ($pengajar->isEmpty() || $kelas->isEmpty() || $mapel->isEmpty()) {
            $this->command->warn('Tidak ada pengajar, kelas, atau mapel. Silakan jalankan seeder lainnya terlebih dahulu.');
            return;
        }
        
        // Assign setiap pengajar ke beberapa mapel secara random
        foreach ($pengajar as $p) {
            // Ambil 3-5 mapel random untuk setiap pengajar
            $randomMapel = $mapel->random(rand(3, min(5, $mapel->count())));
            
            foreach ($randomMapel as $m) {
                // Ambil kelas dari mapel tersebut
                $kelasMapel = $m->kelas;
                
                // Insert ke tabel kelas_pengajar
                DB::table('kelas_pengajar')->updateOrInsert(
                    [
                        'kelas_id' => $kelasMapel->id,
                        'pengajar_id' => $p->id,
                        'mapel_id' => $m->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
        
        $this->command->info('Pengajar berhasil di-assign ke mapel!');
    }
}
