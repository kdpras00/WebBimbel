<?php
/**
 * Script untuk mengecek konfigurasi PHP
 * Akses: http://127.0.0.1:8000/check_php_config.php
 * HAPUS FILE INI SETELAH SELESAI!
 */

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Check PHP Config</title>";
echo "<style>body{font-family:Arial,sans-serif;padding:20px;background:#f5f5f5;}";
echo "table{border-collapse:collapse;background:white;margin:20px 0;}";
echo "th,td{padding:12px;border:1px solid #ddd;}th{background:#4CAF50;color:white;}";
echo ".ok{color:green;font-weight:bold;}.error{color:red;font-weight:bold;}";
echo ".info{background:#e3f2fd;padding:15px;border-left:4px solid #2196F3;margin:20px 0;}";
echo ".warning{background:#fff3cd;padding:15px;border-left:4px solid #ffc107;margin:20px 0;}";
echo "</style></head><body>";

echo "<h1>Konfigurasi PHP Saat Ini</h1>";

echo "<div class='info'>";
echo "<p><strong>File php.ini yang digunakan:</strong><br>";
echo "<code>" . php_ini_loaded_file() . "</code></p>";
echo "<p><strong>Waktu script ini dijalankan:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "<table>";
echo "<tr><th>Setting</th><th>Nilai Saat Ini</th><th>Nilai yang Diinginkan</th><th>Status</th></tr>";

$settings = [
    'upload_max_filesize' => '12M',
    'post_max_size' => '12M',
    'max_execution_time' => '300',
    'max_input_time' => '300',
    'memory_limit' => '256M'
];

$allOk = true;
foreach ($settings as $key => $recommended) {
    $current = ini_get($key);
    $status = '❌ Tidak Sesuai';
    $statusClass = 'error';
    
    if ($key === 'upload_max_filesize' || $key === 'post_max_size') {
        $currentBytes = convertToBytes($current);
        $recommendedBytes = convertToBytes($recommended);
        if ($currentBytes >= $recommendedBytes) {
            $status = '✅ OK';
            $statusClass = 'ok';
        } else {
            $allOk = false;
        }
    } else {
        if ((int)$current >= (int)$recommended) {
            $status = '✅ OK';
            $statusClass = 'ok';
        } else {
            $allOk = false;
        }
    }
    
    echo "<tr>";
    echo "<td><strong>{$key}</strong></td>";
    echo "<td>{$current}</td>";
    echo "<td>{$recommended}</td>";
    echo "<td class='{$statusClass}'>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

if (!$allOk) {
    echo "<div class='warning'>";
    echo "<h3>⚠️ Konfigurasi Belum Sesuai!</h3>";
    echo "<p><strong>Langkah-langkah yang HARUS dilakukan:</strong></p>";
    echo "<ol>";
    echo "<li><strong>Buka XAMPP Control Panel</strong></li>";
    echo "<li><strong>Klik 'Stop' pada Apache</strong> - Tunggu sampai status benar-benar merah/berhenti (bisa 10-30 detik)</li>";
    echo "<li><strong>Tunggu 10 detik</strong> setelah Apache berhenti</li>";
    echo "<li><strong>Klik 'Start' pada Apache</strong> - Tunggu sampai status hijau/running</li>";
    echo "<li><strong>Hard refresh halaman ini:</strong> Tekan <code>Cmd + Shift + R</code> (Mac) atau <code>Ctrl + Shift + R</code> (Windows)</li>";
    echo "</ol>";
    echo "<p><strong>Jika masih tidak berubah:</strong></p>";
    echo "<ol>";
    echo "<li>Stop Apache</li>";
    echo "<li>Tutup XAMPP Control Panel sepenuhnya</li>";
    echo "<li>Buka XAMPP Control Panel lagi</li>";
    echo "<li>Start Apache</li>";
    echo "<li>Atau restart komputer Anda</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='info' style='background:#d4edda;border-color:#28a745;'>";
    echo "<h3>✅ Semua Konfigurasi Sudah Benar!</h3>";
    echo "<p>Konfigurasi PHP sudah sesuai. Anda bisa upload file hingga 10 MB.</p>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>Informasi Tambahan:</h3>";
echo "<p><a href='phpinfo_check.php' target='_blank' style='background:#2196F3;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>Lihat Detail Konfigurasi PHP</a></p>";
echo "<p><a href='?reload=1' style='background:#4CAF50;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>Refresh Halaman</a></p>";

if (isset($_GET['reload'])) {
    echo "<script>setTimeout(function(){location.reload(true);}, 1000);</script>";
}

function convertToBytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

