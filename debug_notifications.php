<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== ALL QUIZ GRADED NOTIFICATIONS ===\n\n";

$notifications = DB::table('notifications')
    ->where('type', 'App\\Notifications\\QuizGraded')
    ->orderBy('created_at', 'desc')
    ->limit(20)
    ->get();

if ($notifications->isEmpty()) {
    echo "⚠️  No QuizGraded notifications found\n";
} else {
    foreach ($notifications as $notif) {
        $user = User::find($notif->notifiable_id);
        $data = json_decode($notif->data, true);
        
        echo "\n📧 Notification ID: {$notif->id}\n";
        echo "   - Created: {$notif->created_at}\n";
        echo "   - Sent to User ID: {$notif->notifiable_id}\n";
        echo "   - User Name: " . ($user ? $user->name : 'UNKNOWN') . "\n";
        echo "   - User Email: " . ($user ? $user->email : 'UNKNOWN') . "\n";
        echo "   - User Role: " . ($user ? $user->role : 'UNKNOWN') . "\n";
        echo "   - Title: " . ($data['title'] ?? 'N/A') . "\n";
        echo "   - Message: " . ($data['message'] ?? 'N/A') . "\n";
        echo "   - Link: " . ($data['link'] ?? 'N/A') . "\n";
        echo "   - Read: " . ($notif->read_at ? 'YES' : 'NO') . "\n";
        
        // Extract result ID from link
        if (isset($data['link']) && preg_match('/\/result\/(\d+)/', $data['link'], $matches)) {
            $resultId = $matches[1];
            $result = \App\Models\QuizResult::find($resultId);
            
            if ($result) {
                echo "   - Result belongs to: Siswa ID {$result->siswa_id} ({$result->siswa->name})\n";
                
                if ($user && $user->id == $result->siswa_id) {
                    echo "   - ✅ CORRECT: Notification sent to result owner\n";
                } else {
                    echo "   - ❌ WRONG: Notification sent to DIFFERENT user!\n";
                    echo "   - 🔧 FIX: This notification should be sent to Siswa ID {$result->siswa_id}\n";
                }
            }
        }
        
        echo "   " . str_repeat('-', 70) . "\n";
    }
}

echo "\n=== CHECKING PRODUCTION SERVER LOGS ===\n";
echo "Please check your production server logs at:\n";
echo "  - /home/kure8737/WebBimbel/storage/logs/laravel.log\n";
echo "\nLook for lines containing:\n";
echo "  - 'Unauthorized quiz result access attempt'\n";
echo "  - The auth_user_id and result_siswa_id values\n";
echo "\n";
