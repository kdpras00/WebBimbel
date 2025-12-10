<?php

/**
 * Debug script untuk melihat informasi quiz result
 * Akses via browser: https://bimbelhikari.my.id/debug_quiz_result.php?id=1
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Start session
if (!session_id()) {
    session_start();
}

use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Get result ID from query parameter
$resultId = $_GET['id'] ?? 1;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result Debug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .error {
            color: #f44336;
            font-weight: bold;
        }
        .warning {
            color: #ff9800;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>🔍 Quiz Result Debug - ID: <?= htmlspecialchars($resultId) ?></h1>
    
    <div class="section">
        <h2>1. Authentication Status</h2>
        <table>
            <?php if (Auth::check()): ?>
                <?php $user = Auth::user(); ?>
                <tr>
                    <th>Status</th>
                    <td class="success">✓ Logged In</td>
                </tr>
                <tr>
                    <th>User ID</th>
                    <td><?= $user->id ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?= htmlspecialchars($user->name) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($user->email) ?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><?= htmlspecialchars($user->role) ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <th>Status</th>
                    <td class="error">✗ Not Logged In</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="section">
        <h2>2. Quiz Result Information</h2>
        <?php
        try {
            $result = QuizResult::with(['siswa', 'quiz'])->find($resultId);
            
            if ($result):
        ?>
            <table>
                <tr>
                    <th>Result ID</th>
                    <td><?= $result->id ?></td>
                </tr>
                <tr>
                    <th>Quiz ID</th>
                    <td><?= $result->quiz_id ?></td>
                </tr>
                <tr>
                    <th>Quiz Title</th>
                    <td><?= htmlspecialchars($result->quiz->judul ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Siswa ID (Owner)</th>
                    <td><?= $result->siswa_id ?></td>
                </tr>
                <tr>
                    <th>Siswa Name (Owner)</th>
                    <td><?= htmlspecialchars($result->siswa->name ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Siswa Email (Owner)</th>
                    <td><?= htmlspecialchars($result->siswa->email ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Nilai</th>
                    <td><?= $result->nilai ?></td>
                </tr>
                <tr>
                    <th>Attempt</th>
                    <td><?= $result->attempt ?></td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?= $result->created_at ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p class="error">✗ Quiz Result with ID <?= $resultId ?> not found!</p>
        <?php endif; ?>
        <?php } catch (\Exception $e): ?>
            <p class="error">✗ Error: <?= htmlspecialchars($e->getMessage()) ?></p>
        <?php endtry; ?>
    </div>

    <div class="section">
        <h2>3. Access Check</h2>
        <?php if (Auth::check() && isset($result) && $result): ?>
            <?php
            $authUserId = Auth::id();
            $resultSiswaId = $result->siswa_id;
            $hasAccess = ($authUserId === $resultSiswaId);
            ?>
            <table>
                <tr>
                    <th>Your User ID</th>
                    <td><?= $authUserId ?></td>
                </tr>
                <tr>
                    <th>Result Owner ID</th>
                    <td><?= $resultSiswaId ?></td>
                </tr>
                <tr>
                    <th>IDs Match?</th>
                    <td class="<?= $hasAccess ? 'success' : 'error' ?>">
                        <?= $hasAccess ? '✓ YES - You should have access' : '✗ NO - This is why you get 403!' ?>
                    </td>
                </tr>
            </table>
            
            <?php if (!$hasAccess): ?>
                <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ff9800;">
                    <h3 style="margin-top: 0;">⚠️ Problem Identified</h3>
                    <p>You are logged in as <strong><?= htmlspecialchars(Auth::user()->name) ?></strong> (ID: <?= $authUserId ?>)</p>
                    <p>But this quiz result belongs to <strong><?= htmlspecialchars($result->siswa->name ?? 'Unknown') ?></strong> (ID: <?= $resultSiswaId ?>)</p>
                    <p><strong>Solution:</strong> You need to login as the correct user who took this quiz.</p>
                </div>
            <?php endif; ?>
        <?php elseif (!Auth::check()): ?>
            <p class="error">✗ You are not logged in. Please login first.</p>
        <?php else: ?>
            <p class="warning">⚠ Cannot perform access check - result not found</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>4. All Quiz Results in Database</h2>
        <?php
        try {
            $allResults = QuizResult::with('siswa')->orderBy('id', 'desc')->limit(10)->get();
            
            if ($allResults->count() > 0):
        ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Quiz ID</th>
                        <th>Siswa ID</th>
                        <th>Siswa Name</th>
                        <th>Nilai</th>
                        <th>Attempt</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allResults as $r): ?>
                    <tr>
                        <td><?= $r->id ?></td>
                        <td><?= $r->quiz_id ?></td>
                        <td><?= $r->siswa_id ?></td>
                        <td><?= htmlspecialchars($r->siswa->name ?? 'N/A') ?></td>
                        <td><?= $r->nilai ?></td>
                        <td><?= $r->attempt ?></td>
                        <td><?= $r->created_at->format('Y-m-d H:i') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin-top: 10px; color: #666; font-size: 14px;">Showing latest 10 results</p>
        <?php else: ?>
            <p class="warning">⚠ No quiz results found in database</p>
        <?php endif; ?>
        <?php } catch (\Exception $e): ?>
            <p class="error">✗ Error: <?= htmlspecialchars($e->getMessage()) ?></p>
        <?php endtry; ?>
    </div>

    <div class="section">
        <h2>5. Session Information</h2>
        <table>
            <tr>
                <th>Session ID</th>
                <td><?= session_id() ?></td>
            </tr>
            <tr>
                <th>Session Data</th>
                <td><pre><?= print_r($_SESSION, true) ?></pre></td>
            </tr>
        </table>
    </div>
</body>
</html>
