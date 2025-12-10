<?php

/**
 * Script untuk update URL notifikasi yang sudah ada di database
 * dari http://127.0.0.1:8000 ke https://bimbelhikari.my.id
 * 
 * Cara menjalankan:
 * php fix_existing_notifications.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$oldUrl = 'http://127.0.0.1:8000';
$newUrl = 'https://bimbelhikari.my.id';

echo "Mencari notifikasi dengan URL lama...\n";

// Get all notifications with old URL - search for IP address pattern
$notifications = DB::table('notifications')
    ->where('data', 'like', '%127.0.0.1%')
    ->get();

echo "Ditemukan " . $notifications->count() . " notifikasi dengan URL lama.\n\n";

if ($notifications->count() === 0) {
    echo "Tidak ada notifikasi yang perlu diupdate.\n";
    exit(0);
}

$updated = 0;

foreach ($notifications as $notification) {
    $data = json_decode($notification->data, true);
    
    // Replace old URL with new URL in the link field
    if (isset($data['link'])) {
        $oldLink = $data['link'];
        $newLink = str_replace($oldUrl, $newUrl, $oldLink);
        
        if ($oldLink !== $newLink) {
            $data['link'] = $newLink;
            
            // Update the notification
            DB::table('notifications')
                ->where('id', $notification->id)
                ->update([
                    'data' => json_encode($data)
                ]);
            
            $updated++;
            echo "✓ Updated: {$oldLink} → {$newLink}\n";
        }
    }
}

echo "\n";
echo "========================================\n";
echo "Selesai! Total {$updated} notifikasi berhasil diupdate.\n";
echo "========================================\n";
