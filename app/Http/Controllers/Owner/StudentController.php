<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(20)
            ->through(function ($student) {
                $results = QuizResult::where('siswa_id', $student->id)->get();
                $student->total_quiz = $results->count();
                $student->avg_score = $results->avg('nilai') ?? 0;
                return $student;
            });

        return view('owner.students.index', compact('students'));
    }

    public function show($id)
    {
        $student = User::where('role', 'siswa')->findOrFail($id);
        
        $results = QuizResult::where('siswa_id', $id)
            ->with(['quiz.mapel', 'quiz.pengajar'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_quiz' => $results->count(),
            'avg_score' => $results->avg('nilai') ?? 0,
            'highest_score' => $results->max('nilai') ?? 0,
            'lowest_score' => $results->min('nilai') ?? 0,
        ];

        return view('owner.students.show', compact('student', 'results', 'stats'));
    }
}
