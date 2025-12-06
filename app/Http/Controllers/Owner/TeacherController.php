<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'pengajar')
            ->with(['quizzes' => function($q) {
                $q->withCount('results');
                $q->withAvg('results', 'nilai');
            }])
            ->get()
            ->map(function ($teacher) {
                $total_quizzes = $teacher->quizzes->count();
                $avg_score = 0;
                $total_results = 0;
                $sum_scores = 0;

                foreach ($teacher->quizzes as $quiz) {
                    $quiz_results_count = $quiz->results_count;
                    $quiz_avg = $quiz->results_avg_nilai;
                    
                    if ($quiz_results_count > 0) {
                        $sum_scores += $quiz_avg * $quiz_results_count;
                        $total_results += $quiz_results_count;
                    }
                }

                $real_avg = $total_results > 0 ? $sum_scores / $total_results : 0;

                return (object) [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'total_quiz' => $total_quizzes,
                    'total_results' => $total_results,
                    'avg_score' => $real_avg,
                    'created_at' => $teacher->created_at,
                ];
            })
            ->sortByDesc('avg_score');

        return view('owner.teachers.index', compact('teachers'));
    }

    public function show($id)
    {
        $teacher = User::with(['quizzes' => function($q) {
                $q->with('mapel');
                $q->withCount('results');
                $q->withAvg('results', 'nilai');
            }])
            ->findOrFail($id);

        // Teacher Stats
        $total_quizzes = $teacher->quizzes->count();
        $total_results = 0;
        $sum_scores = 0;

        foreach ($teacher->quizzes as $quiz) {
            $quiz_results_count = $quiz->results_count;
            $quiz_avg = $quiz->results_avg_nilai;
            
            if ($quiz_results_count > 0) {
                $sum_scores += $quiz_avg * $quiz_results_count;
                $total_results += $quiz_results_count;
            }
        }

        $avg_score = $total_results > 0 ? $sum_scores / $total_results : 0;

        return view('owner.teachers.show', compact('teacher', 'avg_score', 'total_results'));
    }
}
