<?php

/**
 * Fix Old Notifications Script
 * 
 * Script ini akan update semua notifikasi lama yang masih menggunakan URL localhost
 * menjadi URL production yang benar
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>Fix Old Notifications</h1>";
echo "<style>body{font-family:monospace;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

$oldUrl = 'http://127.0.0.1:8000';
$newUrl = config('app.url');

echo "<p class='info'>Old URL: <strong>{$oldUrl}</strong></p>";
echo "<p class='info'>New URL: <strong>{$newUrl}</strong></p>";

// Check notifications with localhost URLs
$notifications = DB::table('notifications')
    ->whereRaw("JSON_EXTRACT(data, '$.link') LIKE ?", ["%{$oldUrl}%"])
    ->get();

echo "<h2>Found Notifications with Localhost URLs</h2>";
echo "<p class='info'>Total: <strong>" . $notifications->count() . "</strong> notifications</p>";

if ($notifications->count() > 0) {
    echo "<h3>Updating notifications...</h3>";
    
    $updated = 0;
    foreach ($notifications as $notification) {
        $data = json_decode($notification->data, true);
        
        // Replace localhost URL with production URL
        if (isset($data['link'])) {
            $oldLink = $data['link'];
            $newLink = str_replace($oldUrl, $newUrl, $oldLink);
            $data['link'] = $newLink;
            
            // Update notification
            DB::table('notifications')
                ->where('id', $notification->id)
                ->update(['data' => json_encode($data)]);
            
            echo "<p class='success'>✅ Updated: {$oldLink} → {$newLink}</p>";
            $updated++;
        }
    }
    
    echo "<hr>";
    echo "<h2 class='success'>✅ Successfully updated {$updated} notifications!</h2>";
} else {
    echo "<p class='success'>✅ No notifications with localhost URLs found. All good!</p>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Refresh halaman notifikasi</li>";
echo "<li>Klik notifikasi untuk test apakah sudah mengarah ke URL yang benar</li>";
echo "<li>Hapus file ini setelah selesai: <code>rm test_fix_notifications.php</code></li>";
echo "</ol>";
