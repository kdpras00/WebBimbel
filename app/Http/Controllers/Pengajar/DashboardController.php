<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_materi' => Materi::where('pengajar_id', $user->id)->count(),
            'total_quiz' => Quiz::where('pengajar_id', $user->id)->count(),
            'total_quiz_published' => Quiz::where('pengajar_id', $user->id)->where('is_published', true)->count(),
            'total_siswa_mengerjakan' => QuizResult::whereHas('quiz', function($query) use ($user) {
                $query->where('pengajar_id', $user->id);
            })->distinct('siswa_id')->count('siswa_id'),
        ];

        $recentMateri = Materi::where('pengajar_id', $user->id)
            ->with('mapel')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentQuiz = Quiz::where('pengajar_id', $user->id)
            ->with('mapel')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch announcements (same logic as Siswa/Wali dashboard)
        $informasi = Informasi::where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();

        return view('pengajar.dashboard', compact('stats', 'recentMateri', 'recentQuiz', 'informasi'));
    }
}
