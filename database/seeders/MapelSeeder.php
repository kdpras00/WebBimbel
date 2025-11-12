<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mapel;
use App\Models\Kelas;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = Kelas::all();

        $mapelList = [
            'Matematika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Bahasa Indonesia',
            'Bahasa Inggris',
        ];

        foreach ($kelas as $kelasItem) {
            foreach ($mapelList as $mapelName) {
                Mapel::create([
                    'nama' => $mapelName,
                    'deskripsi' => "Mata pelajaran {$mapelName} untuk {$kelasItem->nama}",
                    'kelas_id' => $kelasItem->id,
                ]);
            }
        }
    }
}
