<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'owner@bimbel.com'],
            [
                'name' => 'Pemilik Bimbel',
                'password' => Hash::make('password'),
                'role' => 'pemilik',
            ]
        );
    }
}
