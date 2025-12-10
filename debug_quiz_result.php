<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG QUIZ RESULT ACCESS ===\n\n";

// Get quiz result ID from command line or use default
$resultId = $argv[1] ?? 1;

echo "Checking Quiz Result ID: {$resultId}\n\n";

// Get the quiz result
$result = QuizResult::with(['siswa', 'quiz'])->find($resultId);

if (!$result) {
    echo "❌ Quiz Result ID {$resultId} NOT FOUND!\n";
    exit(1);
}

echo "✅ Quiz Result Found:\n";
echo "   - Result ID: {$result->id}\n";
echo "   - Quiz: {$result->quiz->judul}\n";
echo "   - Siswa ID: {$result->siswa_id}\n";
echo "   - Siswa Name: {$result->siswa->name}\n";
echo "   - Siswa Email: {$result->siswa->email}\n";
echo "   - Siswa Role: {$result->siswa->role}\n";
echo "   - Nilai: {$result->nilai}\n";
echo "   - Created At: {$result->created_at}\n\n";

// Check notifications for this result
echo "=== NOTIFICATIONS FOR THIS RESULT ===\n";
$notifications = DB::table('notifications')
    ->whereJsonContains('data->link', route('siswa.quiz.result', $result->id, false))
    ->orWhereJsonContains('data->link', url(route('siswa.quiz.result', $result->id, false)))
    ->get();

if ($notifications->isEmpty()) {
    echo "⚠️  No notifications found with this result link\n";
} else {
    foreach ($notifications as $notif) {
        $user = User::find($notif->notifiable_id);
        $data = json_decode($notif->data, true);
        
        echo "\n📧 Notification ID: {$notif->id}\n";
        echo "   - Sent to User ID: {$notif->notifiable_id}\n";
        echo "   - User Name: " . ($user ? $user->name : 'UNKNOWN') . "\n";
        echo "   - User Email: " . ($user ? $user->email : 'UNKNOWN') . "\n";
        echo "   - User Role: " . ($user ? $user->role : 'UNKNOWN') . "\n";
        echo "   - Notification Type: {$notif->type}\n";
        echo "   - Link: " . ($data['link'] ?? 'N/A') . "\n";
        echo "   - Read At: " . ($notif->read_at ?? 'UNREAD') . "\n";
        
        // Check if this user can access the result
        if ($user && $user->id == $result->siswa_id) {
            echo "   - ✅ ACCESS: ALLOWED (User owns this result)\n";
        } elseif ($user && $user->role == 'wali' && $user->id == $result->siswa->wali_id) {
            echo "   - ⚠️  ACCESS: This is WALI, not SISWA (should use different route)\n";
        } else {
            echo "   - ❌ ACCESS: FORBIDDEN (User does NOT own this result)\n";
        }
    }
}

echo "\n=== SOLUTION ===\n";
echo "If you see '❌ ACCESS: FORBIDDEN', the notification was sent to the wrong user.\n";
echo "The link should only be sent to:\n";
echo "  - Siswa ID: {$result->siswa_id} ({$result->siswa->name})\n";
if ($result->siswa->wali) {
    echo "  - Wali should get route('wali.nilai') instead\n";
}

echo "\n";
