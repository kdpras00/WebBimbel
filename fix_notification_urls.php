<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIX NOTIFICATION URLs ===\n\n";

// Get current APP_URL from config
$newBaseUrl = config('app.url');
echo "Current APP_URL: {$newBaseUrl}\n\n";

// Find all notifications with old localhost URLs
$notifications = DB::table('notifications')
    ->where('data', 'like', '%http://127.0.0.1:8000%')
    ->orWhere('data', 'like', '%http://localhost:8000%')
    ->get();

echo "Found " . $notifications->count() . " notifications with old URLs\n\n";

if ($notifications->isEmpty()) {
    echo "✅ No notifications need updating!\n";
    exit(0);
}

$updated = 0;
foreach ($notifications as $notif) {
    $data = json_decode($notif->data, true);
    $oldData = $notif->data;
    
    // Replace old URLs with new base URL
    $newData = str_replace('http://127.0.0.1:8000', $newBaseUrl, $notif->data);
    $newData = str_replace('http://localhost:8000', $newBaseUrl, $newData);
    
    if ($newData !== $oldData) {
        DB::table('notifications')
            ->where('id', $notif->id)
            ->update(['data' => $newData]);
        
        $updated++;
        
        $dataArray = json_decode($newData, true);
        echo "✅ Updated Notification ID: {$notif->id}\n";
        echo "   Old Link: " . (json_decode($oldData, true)['link'] ?? 'N/A') . "\n";
        echo "   New Link: " . ($dataArray['link'] ?? 'N/A') . "\n\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total notifications updated: {$updated}\n";
echo "\n✅ Done! All notification URLs have been updated to: {$newBaseUrl}\n";
echo "\nNext steps:\n";
echo "1. Clear config cache: php artisan config:clear\n";
echo "2. Clear route cache: php artisan route:clear\n";
echo "3. Clear view cache: php artisan view:clear\n";
echo "4. Clear application cache: php artisan cache:clear\n";
echo "\n";
