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

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get kelas and jurusan from pivot table
        $kelasSiswa = $user->kelasSiswa()->withPivot('jurusan')->get();
        
        $query = Quiz::where('is_published', true)
            ->whereHas('mapel.kelas', function($q) use ($kelasSiswa) {
                $kelasIds = $kelasSiswa->pluck('id');
                $q->whereIn('kelas.id', $kelasIds);
            })
            ->with('mapel');

        // Filter by kelas and jurusan
        $query->where(function($q) use ($kelasSiswa) {
            foreach ($kelasSiswa as $kelas) {
                $kelasId = $kelas->id;
                $jurusanSiswa = $kelas->pivot->jurusan;
                
                $q->orWhere(function($subQ) use ($kelasId, $jurusanSiswa) {
                    $subQ->whereHas('mapel', function($mapelQ) use ($kelasId) {
                        $mapelQ->where('kelas_id', $kelasId);
                    });
                    
                    // If siswa has jurusan, filter by jurusan match or null
                    // If siswa has no jurusan (kelas 1-6), only show quiz with null jurusan
                    if ($jurusanSiswa) {
                        $subQ->where(function($jurusanQ) use ($jurusanSiswa) {
                            $jurusanQ->where('jurusan', $jurusanSiswa)
                                     ->orWhereNull('jurusan');
                        });
                    } else {
                        $subQ->whereNull('jurusan');
                    }
                });
            }
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('mapel', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('mapel.kelas', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $quizzes = $query->get();

        // Get quiz results untuk user ini
        $quizResults = QuizResult::where('siswa_id', $user->id)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->groupBy('quiz_id')
            ->map(function ($results) {
                return [
                    'count' => $results->count(),
                    'max_attempt' => $results->max('attempt'),
                    'latest_result' => $results->sortByDesc('created_at')->first(),
                    'best_score' => $results->max('nilai') ?? 0,
                ];
            });

        return view('siswa.quiz.index', compact('quizzes', 'quizResults'));
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();
        
        // Check attempt count
        $attemptCount = QuizResult::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->count();

        // Maksimal 3 kali attempt
        if ($attemptCount >= 3) {
            $latestResult = QuizResult::where('quiz_id', $id)
                ->where('siswa_id', $user->id)
                ->latest()
                ->first();
            
            return redirect()->route('siswa.quiz.result', $latestResult->id)
                ->with('error', 'Anda sudah mencapai batas maksimal 3 kali attempt untuk quiz ini.');
        }

        // Check if there's an active session that's not submitted
        $activeSession = QuizSession::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->where('status', '!=', 'submitted')
            ->first();

        if ($activeSession) {
            // Continue existing session
            $session = $activeSession;
        } else {
            // Check if there's a completed result but still can retry
            $latestResult = QuizResult::where('quiz_id', $id)
                ->where('siswa_id', $user->id)
                ->latest()
                ->first();

            if ($latestResult && $attemptCount < 3) {
                // User can retry, delete old session and create new one
                QuizSession::where('quiz_id', $id)
                    ->where('siswa_id', $user->id)
                    ->delete();
            }

            // Use updateOrCreate to avoid duplicate entry error
            // This will update existing session or create new one
            $session = QuizSession::updateOrCreate(
                [
                    'quiz_id' => $quiz->id,
                    'siswa_id' => $user->id,
                ],
                [
                    'status' => 'active',
                    'started_at' => now(),
                    'last_resumed_at' => now(),
                    'server_remaining_seconds' => $quiz->durasi ? $quiz->durasi * 60 : null,
                    'warning_count' => 0,
                    'paused_at' => null,
                    'submitted_at' => null,
                ]
            );
        }

        if ($session->status !== 'submitted') {
            $session->update([
                'status' => 'active',
                'paused_at' => null,
                'last_resumed_at' => now(),
                'started_at' => $session->started_at ?? now(),
            ]);
        }

        $session->refresh();

        // Randomize questions and options if not already randomized
        if (empty($session->question_order) || empty($session->option_mapping)) {
            $questions = $quiz->questions->shuffle();
            $questionOrder = $questions->pluck('id')->toArray();
            
            $optionMapping = [];
            $randomizedQuestions = collect();
            
            foreach ($questions as $question) {
                if ($question->tipe == 'pilihan_ganda' && is_array($question->pilihan)) {
                    // Acak urutan pilihan jawaban
                    $pilihan = $question->pilihan;
                    $originalKeys = array_keys($pilihan);
                    
                    // Buat array pasangan key-value untuk diacak
                    $pairs = [];
                    foreach ($originalKeys as $key) {
                        $pairs[] = ['key' => $key, 'value' => $pilihan[$key]];
                    }
                    
                    // Acak urutan pasangan
                    shuffle($pairs);
                    
                    // Buat pilihan baru dengan urutan yang diacak
                    // Keys tetap sama (A, B, C, D), tapi values diacak
                    $shuffledPilihan = [];
                    $reverseMapping = []; // shuffled_key => original_key
                    
                    foreach ($pairs as $i => $pair) {
                        $shuffledKey = $originalKeys[$i]; // Gunakan key asli sesuai urutan
                        $originalKey = $pair['key']; // Key asli yang memiliki value ini
                        $shuffledPilihan[$shuffledKey] = $pair['value'];
                        // Mapping: jika user memilih shuffledKey, itu berarti mereka memilih originalKey
                        $reverseMapping[$shuffledKey] = $originalKey;
                    }
                    
                    // Buat mapping: shuffled_key => original_key
                    $optionMapping[$question->id] = $reverseMapping;
                    
                    // Clone question dan set pilihan yang sudah diacak
                    $randomizedQuestion = clone $question;
                    $randomizedQuestion->setAttribute('pilihan', $shuffledPilihan);
                    $randomizedQuestions->push($randomizedQuestion);
                } else {
                    // Untuk essay, tidak perlu diacak
                    $randomizedQuestions->push($question);
                }
            }
            
            // Simpan randomization ke session
            $session->update([
                'question_order' => $questionOrder,
                'option_mapping' => $optionMapping,
            ]);
            
            $quiz->setRelation('questions', $randomizedQuestions);
        } else {
            // Gunakan urutan yang sudah diacak
            $questionOrder = $session->question_order;
            $optionMapping = $session->option_mapping;
            
            // Reorder questions sesuai dengan question_order
            $questionsById = $quiz->questions->keyBy('id');
            $randomizedQuestions = collect();
            
            foreach ($questionOrder as $questionId) {
                if (isset($questionsById[$questionId])) {
                    $question = $questionsById[$questionId];
                    
                    // Apply option mapping jika ada
                    if ($question->tipe == 'pilihan_ganda' && isset($optionMapping[$questionId]) && is_array($question->pilihan)) {
                        $pilihan = $question->pilihan;
                        $mapping = $optionMapping[$questionId];
                        
                        // Reorder pilihan sesuai mapping yang sudah disimpan
                        $shuffledPilihan = [];
                        foreach ($mapping as $shuffledKey => $originalKey) {
                            if (isset($pilihan[$originalKey])) {
                                $shuffledPilihan[$shuffledKey] = $pilihan[$originalKey];
                            }
                        }
                        
                        // Clone question dan set pilihan yang sudah diacak
                        $randomizedQuestion = clone $question;
                        $randomizedQuestion->setAttribute('pilihan', $shuffledPilihan);
                        $randomizedQuestions->push($randomizedQuestion);
                    } else {
                        $randomizedQuestions->push($question);
                    }
                }
            }
            
            $quiz->setRelation('questions', $randomizedQuestions);
        }

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
        
        // Get option mapping from session if exists
        $optionMapping = $session ? $session->option_mapping : [];
        
        // Convert all shuffled answers back to original before processing
        $convertedJawaban = [];
        foreach ($jawaban as $questionId => $userAnswer) {
            if (isset($optionMapping[$questionId]) && is_array($optionMapping[$questionId])) {
                $mapping = $optionMapping[$questionId];
                // Convert shuffled key to original key
                if (isset($mapping[$userAnswer])) {
                    $convertedJawaban[$questionId] = $mapping[$userAnswer];
                } else {
                    $convertedJawaban[$questionId] = $userAnswer;
                }
            } else {
                $convertedJawaban[$questionId] = $userAnswer;
            }
        }

        // Calculate total possible score (use skor if available, otherwise default to 1 per question)
        $totalMaxSkor = 0;
        foreach ($quiz->questions as $question) {
            $totalMaxSkor += $question->skor ?? 1;
        }
        
        foreach ($quiz->questions as $question) {
            $userAnswer = $convertedJawaban[$question->id] ?? null;
            
            if ($userAnswer && $userAnswer == $question->jawaban_benar) {
                $jawabanBenar++;
                $totalSkor += $question->skor ?? 1;
            }
        }

        // Calculate nilai based on total possible score
        // If no skor system, calculate based on number of correct answers
        if ($totalMaxSkor > 0) {
            $nilai = ($totalSkor / $totalMaxSkor) * 100;
        } else {
            // Fallback: calculate based on correct answers count
            $nilai = $totalSoal > 0 ? ($jawabanBenar / $totalSoal) * 100 : 0;
        }

        // Calculate attempt number
        $previousAttempts = QuizResult::where('quiz_id', $quiz->id)
            ->where('siswa_id', $user->id)
            ->count();
        
        $attemptNumber = $previousAttempts + 1;

        // Check if already reached max attempts
        if ($attemptNumber > 3) {
            return redirect()->route('siswa.quiz.index')
                ->with('error', 'Anda sudah mencapai batas maksimal 3 kali attempt untuk quiz ini.');
        }

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

        // Get randomization data from session
        $questionOrder = $session ? $session->question_order : null;
        $optionMapping = $session ? $session->option_mapping : null;

        $result = QuizResult::create([
            'quiz_id' => $quiz->id,
            'siswa_id' => $user->id,
            'nilai' => round($nilai),
            'total_soal' => $totalSoal,
            'jawaban_benar' => $jawabanBenar,
            'waktu_pengerjaan' => $waktuPengerjaan,
            'attempt' => $attemptNumber,
            'jawaban' => $convertedJawaban, // Save converted answers (original keys)
            'question_order' => $questionOrder, // Save randomized question order
            'option_mapping' => $optionMapping, // Save option mapping for display
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

        // Apply randomization if exists
        $questionOrder = $result->question_order;
        $optionMapping = $result->option_mapping ?? [];
        
        // Create reverse mapping: original_key => shuffled_key for display
        $reverseOptionMapping = [];
        if (!empty($optionMapping) && is_array($optionMapping)) {
            foreach ($optionMapping as $questionId => $mapping) {
                if (is_array($mapping)) {
                    $reverseOptionMapping[$questionId] = array_flip($mapping);
                }
            }
        }

        if (!empty($questionOrder) && is_array($questionOrder)) {
            // Reorder questions sesuai dengan urutan yang diacak
            $questionsById = $result->quiz->questions->keyBy('id');
            $randomizedQuestions = collect();
            
            foreach ($questionOrder as $questionId) {
                if (isset($questionsById[$questionId])) {
                    $question = $questionsById[$questionId];
                    
                    // Apply option mapping jika ada
                    if ($question->tipe == 'pilihan_ganda' && isset($optionMapping[$questionId]) && is_array($optionMapping[$questionId]) && is_array($question->pilihan)) {
                        $pilihan = $question->pilihan;
                        $mapping = $optionMapping[$questionId];
                        $originalJawabanBenar = $question->jawaban_benar; // Simpan jawaban_benar asli
                        
                        // Reorder pilihan sesuai mapping yang sudah disimpan
                        // Mapping: shuffled_key => original_key
                        $shuffledPilihan = [];
                        foreach ($mapping as $shuffledKey => $originalKey) {
                            if (isset($pilihan[$originalKey])) {
                                $shuffledPilihan[$shuffledKey] = $pilihan[$originalKey];
                            }
                        }
                        
                        // Clone question dan set pilihan yang sudah diacak
                        // Juga set jawaban_benar yang sudah dikonversi ke shuffled key
                        $randomizedQuestion = clone $question;
                        $randomizedQuestion->setAttribute('pilihan', $shuffledPilihan);
                        
                        // Convert jawaban_benar to shuffled key
                        if (isset($reverseOptionMapping[$questionId][$originalJawabanBenar])) {
                            $randomizedQuestion->setAttribute('jawaban_benar', $reverseOptionMapping[$questionId][$originalJawabanBenar]);
                        }
                        
                        // Simpan jawaban_benar asli untuk validasi
                        $randomizedQuestion->setAttribute('original_jawaban_benar', $originalJawabanBenar);
                        
                        $randomizedQuestions->push($randomizedQuestion);
                    } else {
                        $randomizedQuestions->push($question);
                    }
                }
            }
            
            $result->quiz->setRelation('questions', $randomizedQuestions);
        }

        return view('siswa.quiz.result', compact('result', 'reverseOptionMapping'));
    }
}
