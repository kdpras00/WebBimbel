<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Counts
        $stats = [
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_pengajar' => User::where('role', 'pengajar')->count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => Mapel::count(),
        ];

        // 2. Global Academic Stats
        $all_results = \App\Models\QuizResult::with('quiz.mapel')->get();
        $total_attempts = $all_results->count();
        
        $global_avg_score = $total_attempts > 0 ? $all_results->avg('nilai') : 0;
        
        $passed_count = $all_results->filter(function ($result) {
            $kkm = $result->quiz->mapel->kkm ?? 70;
            return $result->nilai >= $kkm;
        })->count();

        $pass_rate = $total_attempts > 0 ? ($passed_count / $total_attempts) * 100 : 0;

        $stats['rata_rata_global'] = number_format($global_avg_score, 1);
        $stats['persentase_kelulusan'] = number_format($pass_rate, 1);

        // 3. Teacher Performance
        $teacher_stats = User::where('role', 'pengajar')
            ->with(['quizzes' => function($q) {
                $q->withCount('results');
                $q->withAvg('results', 'nilai');
            }])
            ->get()
            ->map(function ($teacher) {
                $total_quizzes = $teacher->quizzes->count();
                // Calculate weighted average if needed, or just simple average of quiz averages
                // Better: Average of all results belonging to this teacher's quizzes
                $avg_score = 0;
                $total_results = 0;
                $sum_scores = 0;

                foreach ($teacher->quizzes as $quiz) {
                    $quiz_results_count = $quiz->results_count; // using withCount
                    $quiz_avg = $quiz->results_avg_nilai; // using withAvg
                    
                    if ($quiz_results_count > 0) {
                        $sum_scores += $quiz_avg * $quiz_results_count;
                        $total_results += $quiz_results_count;
                    }
                }

                $real_avg = $total_results > 0 ? $sum_scores / $total_results : 0;

                return (object) [
                    'name' => $teacher->name,
                    'total_quiz' => $total_quizzes,
                    'total_results' => $total_results,
                    'avg_score' => $real_avg
                ];
            })
            ->sortByDesc('avg_score') // Sort by best performing students
            ->values();

        // 4. Top Students (by Average Score)
        // Logic: Must be above Global Average AND above standard KKM (e.g. 70)
        $threshold = max($global_avg_score, 70);

        $top_students = \App\Models\QuizResult::select('siswa_id', \Illuminate\Support\Facades\DB::raw('AVG(nilai) as avg_nilai'))
            ->with('siswa')
            ->groupBy('siswa_id')
            ->having('avg_nilai', '>=', $threshold)
            ->orderByDesc('avg_nilai')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact('stats', 'teacher_stats', 'top_students'));
    }
}
