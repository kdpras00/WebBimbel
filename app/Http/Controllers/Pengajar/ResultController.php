<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $quizzes = Quiz::where('pengajar_id', $user->id)
            ->withCount('results')
            ->get();

        $results = QuizResult::whereHas('quiz', function($query) use ($user) {
            $query->where('pengajar_id', $user->id);
        })
        ->with(['quiz.mapel', 'siswa'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Statistics
        $stats = [
            'total_quiz_dikerjakan' => QuizResult::whereHas('quiz', function($query) use ($user) {
                $query->where('pengajar_id', $user->id);
            })->count(),
            'rata_rata_nilai' => QuizResult::whereHas('quiz', function($query) use ($user) {
                $query->where('pengajar_id', $user->id);
            })->avg('nilai') ?? 0,
            'siswa_terbaik' => QuizResult::whereHas('quiz', function($query) use ($user) {
                $query->where('pengajar_id', $user->id);
            })
            ->select('siswa_id', DB::raw('AVG(nilai) as avg_nilai'))
            ->groupBy('siswa_id')
            ->orderBy('avg_nilai', 'desc')
            ->with('siswa')
            ->first(),
        ];

        return view('pengajar.results.index', compact('results', 'quizzes', 'stats'));
    }

    public function show($id)
    {
        $result = QuizResult::with(['quiz.questions', 'siswa', 'feedback'])
            ->whereHas('quiz', function($query) {
                $query->where('pengajar_id', Auth::id());
            })
            ->findOrFail($id);

        return view('pengajar.results.show', compact('result'));
    }
}
