<?php
/**
 * Script untuk melihat konfigurasi PHP yang digunakan oleh web server
 * HAPUS FILE INI SETELAH SELESAI!
 */

echo "<h2>Informasi PHP dari Web Server</h2>";
echo "<p><strong>Loaded Configuration File:</strong> " . php_ini_loaded_file() . "</p>";
echo "<p><strong>Additional .ini files parsed:</strong> " . (php_ini_scanned_files() ?: 'None') . "</p>";

echo "<hr>";
echo "<h3>Konfigurasi Upload:</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Setting</th><th>Nilai Saat Ini</th><th>Lokasi</th></tr>";

$settings = ['upload_max_filesize', 'post_max_size', 'max_execution_time', 'max_input_time', 'memory_limit'];

foreach ($settings as $key) {
    $value = ini_get($key);
    $location = ini_get_all()[$key]['access'] ?? 'unknown';
    echo "<tr>";
    echo "<td><strong>{$key}</strong></td>";
    echo "<td>{$value}</td>";
    echo "<td>{$location}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>Semua Konfigurasi PHP:</h3>";
echo "<p><a href='?full=1'>Klik di sini untuk melihat phpinfo() lengkap</a></p>";

if (isset($_GET['full'])) {
    phpinfo();
}
?>

