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
        $quiz = Quiz::with(['questions', 'mapel.kelas'])->findOrFail($id);
        $user = Auth::user();
        
        // Check attempt count
        $attemptCount = QuizResult::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->count();

        // Maksimal 3 kali attempt - jika sudah 3x, redirect ke hasil terakhir
        if ($attemptCount >= 3) {
            $latestResult = QuizResult::where('quiz_id', $id)
                ->where('siswa_id', $user->id)
                ->latest()
                ->first();
            
            return redirect()->route('siswa.quiz.result', $latestResult->id)
                ->with('info', 'Anda sudah menyelesaikan quiz ini sebanyak 3 kali. Berikut adalah hasil terakhir Anda.');
        }

        // Check if there's an active session
        $activeSession = QuizSession::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->where('status', '!=', 'submitted')
            ->first();

        if ($activeSession) {
            // If session exists, redirect to attempt page directly
            return redirect()->route('siswa.quiz.attempt', $id);
        }

        return view('siswa.quiz.cover', compact('quiz'));
    }

    public function start(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();

        // Check attempt count
        $attemptCount = QuizResult::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->count();

        if ($attemptCount >= 3) {
            return redirect()->route('siswa.quiz.index')
                ->with('error', 'Anda sudah mencapai batas maksimal 3 kali attempt.');
        }

        // Check for ANY existing session (active or submitted)
        $session = QuizSession::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->first();

        if ($session) {
            if ($session->status == 'active') {
                // Session is already active, just resume it
                return redirect()->route('siswa.quiz.attempt', $id);
            }
            
            // If status is submitted (or anything else), and we are here (attempts < 3),
            // we want to RESTART. So we update the existing record to avoid Unique Constraint Violation.
            $session->update([
                'status' => 'active',
                'started_at' => now(),
                'last_resumed_at' => now(),
                'server_remaining_seconds' => $quiz->durasi ? $quiz->durasi * 60 : null,
                'warning_count' => 0,
                'question_order' => null, // Reset randomization for new attempt
                'option_mapping' => null, // Reset randomization for new attempt
                'paused_at' => null,
                'submitted_at' => null,
            ]);
        } else {
            // No session exists, create new one
            $session = QuizSession::create([
                'quiz_id' => $quiz->id,
                'siswa_id' => $user->id,
                'status' => 'active',
                'started_at' => now(),
                'last_resumed_at' => now(),
                'server_remaining_seconds' => $quiz->durasi ? $quiz->durasi * 60 : null,
                'warning_count' => 0,
            ]);
        }

        return redirect()->route('siswa.quiz.attempt', $id);
    }

    public function attempt($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $user = Auth::user();

        $session = QuizSession::where('quiz_id', $id)
            ->where('siswa_id', $user->id)
            ->where('status', '!=', 'submitted')
            ->first();

        if (!$session) {
            return redirect()->route('siswa.quiz.show', $id);
        }

        if ($session->status !== 'submitted') {
            $session->update([
                'status' => 'active',
                'paused_at' => null,
                'last_resumed_at' => now(),
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

        return view('siswa.quiz.attempt', [
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
        
        // Ensure user is authenticated and has siswa role
        if (!$user || $user->role !== 'siswa') {
            \Log::error('Non-siswa user attempted to submit quiz', [
                'user_id' => $user ? $user->id : null,
                'user_role' => $user ? $user->role : null,
                'quiz_id' => $id
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Hanya siswa yang dapat mengumpulkan jawaban quiz.');
        }
        
        $session = null;
        $remainingSeconds = null;
        $waktuPengerjaan = null;

        if ($request->filled('quiz_session_id')) {
            $session = QuizSession::where('id', $request->input('quiz_session_id'))
                ->where('quiz_id', $quiz->id)
                ->where('siswa_id', $user->id)
                ->first();
        }

        // Check if session is already submitted first to prevent double submission
        if ($session && $session->status === 'submitted') {
            $latestResult = QuizResult::where('quiz_id', $quiz->id)
                ->where('siswa_id', $user->id)
                ->latest()
                ->first();
            
            if ($latestResult) {
                return redirect()->route('siswa.quiz.result', $latestResult->id)
                    ->with('warning', 'Quiz sudah disubmit sebelumnya.');
            }
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

        // Calculate Score by comparing answers
        foreach ($quiz->questions as $question) {
            if (isset($convertedJawaban[$question->id])) {
                $userAnswer = $convertedJawaban[$question->id];
                
                if ($question->tipe == 'pilihan_ganda') {
                    // Check if answer matches correct answer
                    if ($userAnswer == $question->jawaban_benar) {
                        $jawabanBenar++;
                    }
                } 
                // For essay, we currently don't auto-grade, or it might be manual. 
                // Assuming essay questions don't contribute to auto-calculated 'jawabanBenar' unless specified.
                // If there were boolean/true-false questions, they would likely fall under pilihan_ganda logic or similar.
            }
        }

        // Calculate nilai based on number of correct answers (Equal weight for all questions)
        // Client request: "menyesuaikan dari soalnya misal soal nya 5 jika jawabannya 5 benar maka dapat 100"
        $nilai = $totalSoal > 0 ? ($jawabanBenar / $totalSoal) * 100 : 0;

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

        \Log::info('Creating QuizResult', [
            'quiz_id' => $quiz->id,
            'siswa_id' => $user->id,
            'nilai' => round($nilai),
            'attempt' => $attemptNumber
        ]);

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

        \Log::info('QuizResult created successfully', [
            'result_id' => $result->id,
            'siswa_id' => $result->siswa_id
        ]);

        // Process gamification
        $settings = \App\Models\GamificationSetting::orderBy('nilai_min', 'desc')->get();
        $points = 0;
        foreach ($settings as $setting) {
            if ($setting->nilai_min !== null && $setting->nilai_max !== null) {
                if ($nilai >= $setting->nilai_min && $nilai <= $setting->nilai_max) {
                    $points += $setting->poin;
                    break;
                }
            } elseif ($setting->nilai_min !== null) {
                if ($nilai >= $setting->nilai_min) {
                    $points += $setting->poin;
                    break;
                }
            }
        }

        $pointRecord = \App\Models\Point::firstOrCreate(
            ['user_id' => $user->id],
            ['total_poin' => 0]
        );
        $pointRecord->total_poin += $points;
        $pointRecord->save();

        // Notify Student
        \Illuminate\Support\Facades\Notification::send($user, new \App\Notifications\QuizGraded($result));

        // Notify Wali if exists
        if ($user->wali) {
            \Illuminate\Support\Facades\Notification::send($user->wali, new \App\Notifications\QuizGraded($result));
        }

        return redirect()->route('siswa.quiz.result', $result->id)
            ->with('success', 'Quiz berhasil disubmit!');
    }

    public function result($id)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat hasil quiz.');
        }

        $result = QuizResult::with(['quiz.questions', 'quiz.mapel'])->findOrFail($id);
        
        // Check if the result belongs to the authenticated user
        if ($result->siswa_id != Auth::id()) {
            \Log::warning('Unauthorized quiz result access attempt', [
                'result_id' => $id,
                'result_siswa_id' => $result->siswa_id,
                'result_siswa_id_type' => gettype($result->siswa_id),
                'auth_user_id' => Auth::id(),
                'auth_user_id_type' => gettype(Auth::id()),
                'auth_user_role' => Auth::user()->role ?? 'unknown'
            ]);
            
            abort(403, 'Anda tidak memiliki akses untuk melihat hasil quiz ini.');
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
