<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $feedback = Feedback::where('pengajar_id', $user->id)
            ->with(['siswa', 'quizResult.quiz'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pengajar.feedback.index', compact('feedback'));
    }

    public function create($resultId = null)
    {
        $user = Auth::user();
        
        $result = null;
        if ($resultId) {
            $result = QuizResult::whereHas('quiz', function($query) use ($user) {
                $query->where('pengajar_id', $user->id);
            })->with('siswa')->findOrFail($resultId);
        }

        $results = QuizResult::whereHas('quiz', function($query) use ($user) {
            $query->where('pengajar_id', $user->id);
        })
        ->with(['siswa', 'quiz'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('pengajar.feedback.create', compact('result', 'results'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'quiz_result_id' => 'nullable|exists:quiz_results,id',
            'komentar' => 'required|string',
        ]);

        $validated['pengajar_id'] = Auth::id();

        Feedback::create($validated);

        return redirect()->route('pengajar.feedback.index')
            ->with('success', 'Feedback berhasil diberikan');
    }

    public function edit($id)
    {
        $feedback = Feedback::where('pengajar_id', Auth::id())->findOrFail($id);
        $user = Auth::user();
        
        $results = QuizResult::whereHas('quiz', function($query) use ($user) {
            $query->where('pengajar_id', $user->id);
        })
        ->with(['siswa', 'quiz'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('pengajar.feedback.edit', compact('feedback', 'results'));
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::where('pengajar_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'quiz_result_id' => 'nullable|exists:quiz_results,id',
            'komentar' => 'required|string',
        ]);

        $feedback->update($validated);

        return redirect()->route('pengajar.feedback.index')
            ->with('success', 'Feedback berhasil diupdate');
    }

    public function destroy($id)
    {
        $feedback = Feedback::where('pengajar_id', Auth::id())->findOrFail($id);
        $feedback->delete();

        return redirect()->route('pengajar.feedback.index')
            ->with('success', 'Feedback berhasil dihapus');
    }
}
