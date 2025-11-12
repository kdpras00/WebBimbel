<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResult;
use App\Models\QuizSession;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $user = Auth::user();
        $kelasIds = $user->kelasSiswa->pluck('id');
        
        $quizzes = Quiz::where('is_published', true)
            ->whereHas('mapel.kelas', function($query) use ($kelasIds) {
                $query->whereIn('kelas.id', $kelasIds);
            })
            ->with('mapel')
            ->get();

        return view('siswa.quiz.index', compact('quizzes'));
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();
        
        // Check if already completed
        $existingResult = QuizResult::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->first();

        if ($existingResult) {
            return redirect()->route('siswa.quiz.result', $existingResult->id);
        }

        $session = QuizSession::firstOrCreate(
            ['quiz_id' => $quiz->id, 'siswa_id' => $user->id],
            [
                'status' => 'active',
                'started_at' => now(),
                'last_resumed_at' => now(),
                'server_remaining_seconds' => $quiz->durasi ? $quiz->durasi * 60 : null,
            ]
        );

        if ($session->status !== 'submitted') {
            $session->update([
                'status' => 'active',
                'paused_at' => null,
                'last_resumed_at' => now(),
                'started_at' => $session->started_at ?? now(),
            ]);
        }

        $session->refresh();

        $remainingSeconds = $session->remainingSeconds();

        if (!is_null($remainingSeconds) && $remainingSeconds <= 0) {
            $session->update([
                'status' => 'submitted',
                'server_remaining_seconds' => 0,
                'submitted_at' => now(),
            ]);

            return redirect()->route('siswa.quiz.index')
                ->with('error', 'Waktu pengerjaan kuis telah habis.');
        }

        $maxWarnings = QuizSessionController::MAX_WARNINGS ?? 3;

        return view('siswa.quiz.show', [
            'quiz' => $quiz,
            'session' => $session,
            'remainingSeconds' => $remainingSeconds,
            'maxWarnings' => $maxWarnings,
        ]);
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();
        
        $session = null;
        $remainingSeconds = null;
        $waktuPengerjaan = null;

        if ($request->filled('quiz_session_id')) {
            $session = QuizSession::where('id', $request->input('quiz_session_id'))
                ->where('quiz_id', $quiz->id)
                ->where('siswa_id', $user->id)
                ->first();
        }

        $endTime = now();

        $jawaban = $request->input('jawaban', []);
        $totalSoal = $quiz->questions->count();
        $jawabanBenar = 0;
        $totalSkor = 0;

        foreach ($quiz->questions as $question) {
            $userAnswer = $jawaban[$question->id] ?? null;
            
            if ($userAnswer && $userAnswer == $question->jawaban_benar) {
                $jawabanBenar++;
                $totalSkor += $question->skor;
            }
        }

        $nilai = ($totalSkor / ($totalSkor > 0 ? $totalSkor : 1)) * 100;

        if ($session) {
            $remainingSeconds = $session->remainingSeconds($endTime);
            $durasiDetik = $quiz->durasi ? $quiz->durasi * 60 : null;

            if (!is_null($durasiDetik) && !is_null($remainingSeconds)) {
                $waktuPengerjaan = max(0, $durasiDetik - $remainingSeconds);
            } else {
                $startedAt = $session->started_at ?? $endTime;
                $waktuPengerjaan = $endTime->diffInSeconds($startedAt);
            }

            $session->update([
                'status' => 'submitted',
                'server_remaining_seconds' => $remainingSeconds,
                'submitted_at' => $endTime,
            ]);
        } else {
            $startTime = $request->input('start_time');

            if (is_numeric($startTime)) {
                $startTime = Carbon::createFromTimestamp($startTime);
            } else {
                $startTime = Carbon::parse($startTime);
            }

            $waktuPengerjaan = max(0, $endTime->diffInSeconds($startTime));
        }

        $result = QuizResult::create([
            'quiz_id' => $quiz->id,
            'siswa_id' => $user->id,
            'nilai' => round($nilai),
            'total_soal' => $totalSoal,
            'jawaban_benar' => $jawabanBenar,
            'waktu_pengerjaan' => $waktuPengerjaan,
            'attempt' => 1,
            'jawaban' => $jawaban,
        ]);

        // Process gamification
        $this->gamificationService->processQuizResult($result);

        return redirect()->route('siswa.quiz.result', $result->id)
            ->with('success', 'Quiz berhasil disubmit!');
    }

    public function result($id)
    {
        $result = QuizResult::with(['quiz.questions', 'quiz.mapel'])->findOrFail($id);
        
        if ($result->siswa_id !== Auth::id()) {
            abort(403);
        }

        return view('siswa.quiz.result', compact('result'));
    }
}
