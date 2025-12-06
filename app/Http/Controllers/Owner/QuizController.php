<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function show($id)
    {
        $quiz = Quiz::with(['mapel', 'pengajar', 'results.siswa'])->findOrFail($id);

        // 1. Identify Target Audience (Students)
        // Find classes where this teacher teaches this mapel
        $target_kelas_ids = DB::table('kelas_pengajar')
            ->where('pengajar_id', $quiz->pengajar_id)
            ->where('mapel_id', $quiz->mapel_id)
            ->pluck('kelas_id');

        // Get all students in these classes
        // If quiz has specific jurusan, filter only students/classes of that jurusan
        $students_query = User::where('role', 'siswa')
            ->whereHas('kelasSiswa', function ($q) use ($target_kelas_ids) {
                $q->whereIn('kelas.id', $target_kelas_ids);
            });

        if ($quiz->jurusan) {
            $students_query->where('jurusan', $quiz->jurusan);
        }

        $all_students = $students_query->get();

        // 2. Map Status for each student
        $student_data = $all_students->map(function ($student) use ($quiz) {
            $result = $quiz->results->where('siswa_id', $student->id)->first();
            
            $status = 'Belum Dikerjakan';
            $score = null;
            $pass_status = null;
            $submitted_at = null;

            if ($result) {
                $status = 'Sudah Dikerjakan';
                $score = $result->nilai;
                $submitted_at = $result->created_at;
                // Assuming passing grade is 70, or we could add a field to Quiz model later
                $pass_status = $score >= 70 ? 'Lulus' : 'Tidak Lulus';
            }

            return (object) [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'status' => $status,
                'score' => $score,
                'pass_status' => $pass_status,
                'submitted_at' => $submitted_at,
            ];
        });

        // 3. Calculate Stats
        $stats = [
            'total_students' => $all_students->count(),
            'submitted' => $student_data->where('status', 'Sudah Dikerjakan')->count(),
            'not_submitted' => $student_data->where('status', 'Belum Dikerjakan')->count(),
            'passed' => $student_data->where('pass_status', 'Lulus')->count(),
            'failed' => $student_data->where('pass_status', 'Tidak Lulus')->count(),
        ];

        return view('owner.quizzes.show', compact('quiz', 'student_data', 'stats'));
    }
}
