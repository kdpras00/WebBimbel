<?php

/**
 * Script untuk testing akses Quiz Result
 * Jalankan dengan: php test_quiz_access.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\QuizResult;
use App\Models\User;
use App\Models\Quiz;

echo "=== Testing Quiz Result Access ===\n\n";

// Test 1: Cek apakah class QuizResult bisa diakses
echo "1. Testing QuizResult class...\n";
try {
    $resultCount = QuizResult::count();
    echo "   ✓ QuizResult class accessible\n";
    echo "   ✓ Total QuizResults in database: {$resultCount}\n\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Cek siswa yang ada
echo "2. Testing Siswa users...\n";
try {
    $siswaCount = User::where('role', 'siswa')->count();
    echo "   ✓ Total Siswa: {$siswaCount}\n";
    
    if ($siswaCount > 0) {
        $siswa = User::where('role', 'siswa')->first();
        echo "   ✓ Sample Siswa: {$siswa->name} (ID: {$siswa->id})\n\n";
    } else {
        echo "   ! No siswa found in database\n\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Cek QuizResult untuk siswa tertentu
echo "3. Testing QuizResult ownership...\n";
try {
    $results = QuizResult::with('siswa')->limit(5)->get();
    
    if ($results->count() > 0) {
        echo "   ✓ Found {$results->count()} QuizResults\n";
        foreach ($results as $result) {
            echo "   - Result ID: {$result->id}, Siswa: {$result->siswa->name} (ID: {$result->siswa_id}), Nilai: {$result->nilai}\n";
        }
        echo "\n";
    } else {
        echo "   ! No QuizResults found\n\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Cek Quiz yang tersedia
echo "4. Testing Quiz availability...\n";
try {
    $quizCount = Quiz::where('is_published', true)->count();
    echo "   ✓ Total Published Quizzes: {$quizCount}\n\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
}

echo "=== Test Complete ===\n";
echo "\nInstruksi:\n";
echo "1. Jika semua test berhasil (✓), coba akses halaman quiz di browser\n";
echo "2. Login sebagai siswa\n";
echo "3. Akses /siswa/quiz untuk melihat daftar quiz\n";
echo "4. Jika masih error 403, cek log dengan: tail -f storage/logs/laravel.log\n";
