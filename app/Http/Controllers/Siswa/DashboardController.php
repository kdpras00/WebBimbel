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

        $allResults = QuizResult::where('siswa_id', $user->id)->get();
        $totalQuiz = $allResults->count();
        $averageScore = $allResults->avg('nilai') ?? 0;
        $highestScore = $allResults->max('nilai') ?? 0;
        $lowestScore = $allResults->min('nilai') ?? 0;
        $latestResult = $allResults->sortByDesc('created_at')->first();
        
        // Limit to only 1 latest active information to prevent UI clutter
        $informasi = \App\Models\Informasi::where('is_active', true)->latest()->take(1)->get();

        return view('siswa.dashboard', compact('totalPoin', 'recentResults', 'totalQuiz', 'averageScore', 'highestScore', 'lowestScore', 'latestResult', 'allResults', 'informasi'));
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
