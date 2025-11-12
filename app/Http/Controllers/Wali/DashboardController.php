<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizResult;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $anak = $user->anak;

        $stats = [];
        foreach ($anak as $child) {
            $point = Point::where('user_id', $child->id)->first();
            $totalPoin = $point ? $point->total_poin : 0;
            
            $totalQuiz = QuizResult::where('siswa_id', $child->id)->count();
            $averageScore = QuizResult::where('siswa_id', $child->id)->avg('nilai') ?? 0;
            
            $rank = $this->getRank($child->id);

            $stats[] = [
                'anak' => $child,
                'total_poin' => $totalPoin,
                'total_quiz' => $totalQuiz,
                'rata_rata_nilai' => round($averageScore, 1),
                'ranking' => $rank,
            ];
        }

        return view('wali.dashboard', compact('stats'));
    }

    public function nilai()
    {
        $user = Auth::user();
        $anak = $user->anak;

        $results = QuizResult::whereIn('siswa_id', $anak->pluck('id'))
            ->with(['quiz.mapel', 'siswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wali.nilai', compact('results', 'anak'));
    }

    public function progress()
    {
        $user = Auth::user();
        $anak = $user->anak;

        $progressData = [];
        foreach ($anak as $child) {
            $results = QuizResult::where('siswa_id', $child->id)
                ->with('quiz.mapel')
                ->orderBy('created_at', 'asc')
                ->get();

            $progressData[] = [
                'anak' => $child,
                'results' => $results,
            ];
        }

        return view('wali.progress', compact('progressData'));
    }

    private function getRank($userId)
    {
        $points = Point::orderBy('total_poin', 'desc')->get();
        $rank = 1;
        
        foreach ($points as $point) {
            if ($point->user_id == $userId) {
                return $rank;
            }
            $rank++;
        }
        
        return null;
    }
}
