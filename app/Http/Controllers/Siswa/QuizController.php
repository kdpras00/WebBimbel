<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResult;
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

        return view('siswa.quiz.show', compact('quiz'));
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();
        
        $startTime = $request->input('start_time');
        $endTime = now();
        
        // Convert timestamp to Carbon instance
        if (is_numeric($startTime)) {
            $startTime = Carbon::createFromTimestamp($startTime);
        } else {
            $startTime = Carbon::parse($startTime);
        }
        
        $waktuPengerjaan = $endTime->diffInSeconds($startTime);
        
        // Ensure waktu_pengerjaan is not negative
        if ($waktuPengerjaan < 0) {
            $waktuPengerjaan = 0;
        }

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
