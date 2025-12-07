<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Point;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Pengajar
        $pengajar1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'pengajar1@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'pengajar',
        ]);

        $pengajar2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'pengajar2@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'pengajar',
        ]);

        // Create Wali
        $wali1 = User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'wali1@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
        ]);

        $wali2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'wali2@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
        ]);

        // Create Siswa
        $siswa1 = User::create([
            'name' => 'Andi Pratama',
            'email' => 'siswa1@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'wali_id' => $wali1->id,
        ]);

        $siswa2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'siswa2@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'wali_id' => $wali2->id,
        ]);

        $siswa3 = User::create([
            'name' => 'Rizki Maulana',
            'email' => 'siswa3@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'wali_id' => $wali1->id,
        ]);

        // Note: Assign siswa to kelas dilakukan di KelasSiswaSeeder dengan jurusan

        // Create points for siswa

    }
}
