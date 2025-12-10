<?php

/**
 * Test URL Generation Script
 * 
 * Upload file ini ke production server dan jalankan via browser
 * untuk test apakah URL generation sudah benar
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h1>URL Generation Test</h1>";
echo "<style>body{font-family:monospace;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

// Test 1: Check APP_URL
echo "<h2>1. APP_URL Configuration</h2>";
$appUrl = config('app.url');
echo "<p class='info'>APP_URL: <strong>{$appUrl}</strong></p>";

if (strpos($appUrl, 'localhost') !== false || strpos($appUrl, '127.0.0.1') !== false) {
    echo "<p class='error'>❌ ERROR: APP_URL masih menggunakan localhost!</p>";
    echo "<p>Ubah APP_URL di .env menjadi: <code>APP_URL=https://bimbelhikari.my.id</code></p>";
} else {
    echo "<p class='success'>✅ APP_URL sudah benar</p>";
}

// Test 2: Test route generation
echo "<h2>2. Route Generation Test</h2>";
try {
    $testRoutes = [
        'siswa.quiz.index' => [],
        'siswa.quiz.result' => [1],
        'dashboard' => [],
    ];
    
    foreach ($testRoutes as $routeName => $params) {
        $url = route($routeName, $params);
        echo "<p class='info'><strong>{$routeName}</strong>: {$url}</p>";
        
        if (strpos($url, 'localhost') !== false || strpos($url, '127.0.0.1') !== false) {
            echo "<p class='error'>❌ Route masih generate URL localhost!</p>";
        } else {
            echo "<p class='success'>✅ Route sudah benar</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

// Test 3: Check cache
echo "<h2>3. Cache Status</h2>";
$configCached = file_exists(base_path('bootstrap/cache/config.php'));
echo "<p class='info'>Config cached: " . ($configCached ? 'Yes' : 'No') . "</p>";

if ($configCached) {
    $cacheTime = filemtime(base_path('bootstrap/cache/config.php'));
    $envTime = filemtime(base_path('.env'));
    
    echo "<p class='info'>Config cache time: " . date('Y-m-d H:i:s', $cacheTime) . "</p>";
    echo "<p class='info'>.env modified time: " . date('Y-m-d H:i:s', $envTime) . "</p>";
    
    if ($cacheTime < $envTime) {
        echo "<p class='error'>❌ Cache lebih lama dari .env! Jalankan: <code>php artisan config:cache</code></p>";
    } else {
        echo "<p class='success'>✅ Cache sudah up-to-date</p>";
    }
}

// Test 4: Environment info
echo "<h2>4. Environment Info</h2>";
echo "<p class='info'>APP_ENV: " . config('app.env') . "</p>";
echo "<p class='info'>APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "</p>";

echo "<hr>";
echo "<h3>Jika masih ada masalah:</h3>";
echo "<ol>";
echo "<li>Jalankan: <code>php artisan config:clear</code></li>";
echo "<li>Jalankan: <code>php artisan config:cache</code></li>";
echo "<li>Jalankan: <code>php artisan cache:clear</code></li>";
echo "<li>Refresh halaman ini</li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Note:</strong> Notifikasi yang sudah dibuat SEBELUM perubahan APP_URL akan tetap menggunakan URL lama. Hanya notifikasi BARU yang akan menggunakan URL yang benar.</p>";
