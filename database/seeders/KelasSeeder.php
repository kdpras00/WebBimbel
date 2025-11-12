<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            ['nama' => 'Kelas 10', 'deskripsi' => 'Kelas 10 SMA'],
            ['nama' => 'Kelas 11', 'deskripsi' => 'Kelas 11 SMA'],
            ['nama' => 'Kelas 12', 'deskripsi' => 'Kelas 12 SMA'],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
}
