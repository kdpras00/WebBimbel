<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizResult;
use App\Models\Point;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            ->with(['quiz.mapel', 'quiz.pengajar', 'siswa', 'feedback.pengajar'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate progress stats for each child
        $progressData = [];
        foreach ($anak as $child) {
            $childResults = QuizResult::where('siswa_id', $child->id)->get();
            $progressData[$child->id] = [
                'avg_score' => $childResults->avg('nilai') ?? 0,
                'total_quiz' => $childResults->count(),
                'best_score' => $childResults->max('nilai') ?? 0,
            ];
        }

        return view('wali.nilai', compact('results', 'anak', 'progressData'));
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

    public function feedback()
    {
        $user = Auth::user();
        $anak = $user->anak;
        
        // Get feedback for all children
        $feedback = Feedback::whereIn('siswa_id', $anak->pluck('id'))
            ->with(['siswa', 'pengajar', 'quizResult.quiz'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wali.feedback', compact('feedback', 'anak'));
    }

    public function downloadPDF(Request $request)
    {
        $user = Auth::user();
        $anak = $user->anak;

        // Get all results for all children
        $results = QuizResult::whereIn('siswa_id', $anak->pluck('id'))
            ->with(['quiz.mapel', 'quiz.pengajar', 'siswa', 'feedback.pengajar'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate progress stats for each child
        $progressData = [];
        foreach ($anak as $child) {
            $childResults = QuizResult::where('siswa_id', $child->id)->get();
            $progressData[$child->id] = [
                'avg_score' => $childResults->avg('nilai') ?? 0,
                'total_quiz' => $childResults->count(),
                'best_score' => $childResults->max('nilai') ?? 0,
            ];
        }

        // Generate PDF
        $html = view('wali.nilai-pdf', compact('results', 'anak', 'progressData', 'user'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Nilai_' . date('Y-m-d') . '.pdf';
        
        return $dompdf->stream($filename);
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
