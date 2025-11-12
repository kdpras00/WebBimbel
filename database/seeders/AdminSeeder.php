<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GamificationSetting;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@bimbel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create default gamification settings
        GamificationSetting::create([
            'nama_aturan' => 'Nilai Sempurna',
            'nilai_min' => 90,
            'nilai_max' => 100,
            'poin' => 20,
            'keterangan' => 'Nilai 90-100 mendapatkan 20 poin',
        ]);

        GamificationSetting::create([
            'nama_aturan' => 'Nilai Baik',
            'nilai_min' => 80,
            'nilai_max' => 89,
            'poin' => 15,
            'keterangan' => 'Nilai 80-89 mendapatkan 15 poin',
        ]);

        GamificationSetting::create([
            'nama_aturan' => 'Nilai Cukup',
            'nilai_min' => 0,
            'nilai_max' => 79,
            'poin' => 10,
            'keterangan' => 'Nilai di bawah 80 mendapatkan 10 poin',
        ]);
    }
}
