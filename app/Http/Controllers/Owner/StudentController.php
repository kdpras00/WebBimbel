<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $student = User::with(['quizResults' => function($q) {
                $q->with(['quiz.mapel', 'quiz.pengajar']);
                $q->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        $stats = [
            'total_quiz' => $student->quizResults->count(),
            'avg_score' => $student->quizResults->avg('nilai') ?? 0,
            'highest_score' => $student->quizResults->max('nilai') ?? 0,
            'lowest_score' => $student->quizResults->min('nilai') ?? 0,
        ];

        return view('owner.students.show', compact('student', 'stats'));
    }

    public function downloadPDF($id)
    {
        $student = User::with(['quizResults' => function($q) {
                $q->with(['quiz.mapel', 'quiz.pengajar']);
                $q->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        $stats = [
            'total_quiz' => $student->quizResults->count(),
            'avg_score' => $student->quizResults->avg('nilai') ?? 0,
            'highest_score' => $student->quizResults->max('nilai') ?? 0,
            'lowest_score' => $student->quizResults->min('nilai') ?? 0,
        ];

        // Generate PDF
        $html = view('owner.students.pdf', compact('student', 'stats'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Progress_' . str_replace(' ', '_', $student->name) . '_' . date('Y-m-d') . '.pdf';
        
        return $dompdf->stream($filename);
    }
}
