<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $point = Point::where('user_id', $user->id)->first();
        $totalPoin = $point ? $point->total_poin : 0;
        
        $recentResults = QuizResult::where('siswa_id', $user->id)
            ->with('quiz')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $totalQuiz = QuizResult::where('siswa_id', $user->id)->count();
        $averageScore = QuizResult::where('siswa_id', $user->id)->avg('nilai') ?? 0;

        return view('siswa.dashboard', compact('totalPoin', 'recentResults', 'totalQuiz', 'averageScore'));
    }

    public function progress()
    {
        $user = Auth::user();
        $results = QuizResult::where('siswa_id', $user->id)
            ->with('quiz.mapel')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.progress', compact('results'));
    }
}
