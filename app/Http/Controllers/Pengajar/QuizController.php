<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $quizzes = Quiz::where('pengajar_id', $user->id)
            ->with('mapel.kelas')
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengajar.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        $user = Auth::user();
        // Ambil mapel yang diajar pengajar melalui relasi pivot
        $mapel = $user->mapelDiajar()
            ->with('kelas')
            ->get();

        return view('pengajar.quiz.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
            'durasi' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        $validated['pengajar_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        $quiz = Quiz::create($validated);

        return redirect()->route('pengajar.quiz.edit', $quiz->id)
            ->with('success', 'Quiz berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit($id)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())
            ->with('questions')
            ->findOrFail($id);

        return view('pengajar.quiz.edit', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $quiz->update($validated);

        return redirect()->route('pengajar.quiz.index')
            ->with('success', 'Quiz berhasil diupdate');
    }

    public function destroy($id)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())->findOrFail($id);
        $quiz->delete();

        return redirect()->route('pengajar.quiz.index')
            ->with('success', 'Quiz berhasil dihapus');
    }

    public function storeQuestion(Request $request, $quizId)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())->findOrFail($quizId);

        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe' => 'required|in:pilihan_ganda,essay',
            'pilihan' => 'required_if:tipe,pilihan_ganda|array',
            'jawaban_benar' => 'required|string',
            'skor' => 'required|integer|min:1',
            'urutan' => 'nullable|integer',
        ]);

        $validated['quiz_id'] = $quiz->id;
        $validated['urutan'] = $validated['urutan'] ?? Question::where('quiz_id', $quiz->id)->max('urutan') + 1;

        if ($validated['tipe'] == 'pilihan_ganda' && is_array($validated['pilihan'])) {
            // Validasi: semua pilihan harus berbeda
            $pilihanValues = array_map('trim', array_values($validated['pilihan']));
            $uniqueValues = array_unique($pilihanValues);
            
            if (count($pilihanValues) !== count($uniqueValues)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pilihan' => 'Semua pilihan jawaban harus berbeda!']);
            }
            
            // Cast akan otomatis encode ke JSON, jadi tidak perlu manual encode
            // Laravel akan otomatis encode saat save
        }

        Question::create($validated);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan');
    }

    public function destroyQuestion($quizId, $questionId)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())->findOrFail($quizId);
        $question = Question::where('quiz_id', $quiz->id)->findOrFail($questionId);
        $question->delete();

        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }
}
