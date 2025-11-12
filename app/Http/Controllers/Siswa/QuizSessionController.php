<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class QuizSessionController extends Controller
{
    public const MAX_WARNINGS = 3;

    public function pause(Request $request, Quiz $quiz): JsonResponse
    {
        $session = $this->resolveSession($request, $quiz);

        if ($session->status === 'submitted') {
            return response()->json([
                'status' => 'submitted',
                'remaining_seconds' => 0,
            ], 409);
        }

        $remaining = $session->remainingSeconds();

        $session->update([
            'status' => 'paused',
            'paused_at' => now(),
            'last_resumed_at' => null,
            'server_remaining_seconds' => $remaining,
        ]);

        return response()->json([
            'status' => 'paused',
            'remaining_seconds' => $remaining,
        ]);
    }

    public function resume(Request $request, Quiz $quiz): JsonResponse
    {
        $session = $this->resolveSession($request, $quiz);

        if ($session->status === 'submitted') {
            return response()->json([
                'status' => 'submitted',
                'remaining_seconds' => 0,
            ], 409);
        }

        $remaining = $session->remainingSeconds();

        if ($remaining === 0 && !is_null($session->server_remaining_seconds)) {
            return response()->json([
                'status' => 'time_up',
                'remaining_seconds' => 0,
            ], 410);
        }

        $session->update([
            'status' => 'active',
            'paused_at' => null,
            'last_resumed_at' => now(),
        ]);

        return response()->json([
            'status' => 'active',
            'remaining_seconds' => $session->remainingSeconds(),
        ]);
    }

    public function warning(Request $request, Quiz $quiz): JsonResponse
    {
        $session = $this->resolveSession($request, $quiz);

        if ($session->status === 'submitted') {
            return response()->json([
                'status' => 'submitted',
                'warning_count' => $session->warning_count,
                'auto_submit' => false,
            ], 409);
        }

        $session->increment('warning_count');
        $session->refresh();

        $autoSubmit = $session->warning_count >= self::MAX_WARNINGS;

        return response()->json([
            'status' => 'warning-recorded',
            'warning_count' => $session->warning_count,
            'auto_submit' => $autoSubmit,
        ]);
    }

    private function resolveSession(Request $request, Quiz $quiz): QuizSession
    {
        $validated = $request->validate([
            'quiz_session_id' => ['required', 'integer'],
        ]);

        $session = QuizSession::where('id', $validated['quiz_session_id'])
            ->where('quiz_id', $quiz->id)
            ->where('siswa_id', $request->user()->id)
            ->first();

        if (!$session) {
            throw ValidationException::withMessages([
                'quiz_session_id' => __('Sesi kuis tidak ditemukan atau sudah berakhir.'),
            ]);
        }

        return $session;
    }
}


