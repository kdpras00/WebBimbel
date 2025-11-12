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
        
        $pengajarCount = $pengajar->count();

        if ($pengajarCount === 0) {
            $this->command->warn('Tidak ada pengguna dengan role pengajar. Seeder dilewati.');
            return;
        }

        // Pastikan setiap mapel minimal memiliki satu pengajar
        $pengajarIndex = 0;

        foreach ($mapel->load('kelas') as $m) {
            if (is_null($m->kelas)) {
                $this->command->warn("Mapel {$m->nama} belum memiliki kelas sehingga dilewati.");
                continue;
            }

            $sudahAdaPengajar = DB::table('kelas_pengajar')
                ->where('mapel_id', $m->id)
                ->exists();

            if (!$sudahAdaPengajar) {
                $pengajarTerpilih = $pengajar[$pengajarIndex % $pengajarCount];

                DB::table('kelas_pengajar')->updateOrInsert(
                    [
                        'kelas_id' => $m->kelas->id,
                        'pengajar_id' => $pengajarTerpilih->id,
                        'mapel_id' => $m->id,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $pengajarIndex++;
            }
        }

        // Tambahan assignment random agar distribusi tetap bervariasi
        foreach ($pengajar as $p) {
            $randomMapel = $mapel->shuffle()->take(rand(2, min(4, $mapel->count())));

            foreach ($randomMapel as $m) {
                if (is_null($m->kelas)) {
                    continue;
                }

                DB::table('kelas_pengajar')->updateOrInsert(
                    [
                        'kelas_id' => $m->kelas->id,
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
