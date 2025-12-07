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

        $jurusanOptions = ['IPA', 'IPS'];

        return view('pengajar.quiz.create', compact('mapel', 'jurusanOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
            'durasi' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'jurusan' => 'nullable|string|max:50',
        ]);

        // Validasi jurusan berdasarkan kelas
        $mapel = \App\Models\Mapel::with('kelas')->findOrFail($validated['mapel_id']);
        $kelasNama = $mapel->kelas->nama ?? '';
        preg_match('/\d+/', $kelasNama, $matches);
        $kelasNumber = !empty($matches) ? (int)$matches[0] : 0;

        // Jika kelas 1-9, jurusan harus kosong
        if ($kelasNumber >= 1 && $kelasNumber <= 9) {
            $validated['jurusan'] = null;
        } elseif ($kelasNumber >= 10 && $kelasNumber <= 12) {
            // Jika kelas 10-12, jurusan harus diisi (tidak boleh kosong)
            if (empty($validated['jurusan'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jurusan' => 'Jurusan harus dipilih untuk kelas 10-12.']);
            }
        }

        $validated['pengajar_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        $quiz = Quiz::create($validated);

        // Notify students in the class
        if ($mapel->kelas && $mapel->kelas->siswa->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($mapel->kelas->siswa, new \App\Notifications\NewContent($quiz, 'quiz'));
        }

        return redirect()->route('pengajar.quiz.edit', $quiz->id)
            ->with('success', 'Quiz berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit($id)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())
            ->with('questions')
            ->with('mapel.kelas')
            ->findOrFail($id);

        $jurusanOptions = ['IPA', 'IPS'];

        return view('pengajar.quiz.edit', compact('quiz', 'jurusanOptions'));
    }

    public function update(Request $request, $id)
    {
        $quiz = Quiz::where('pengajar_id', Auth::id())
            ->with('mapel.kelas')
            ->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'jurusan' => 'nullable|string|max:50',
        ]);

        // Validasi jurusan berdasarkan kelas dari quiz yang sudah ada
        $kelasNama = $quiz->mapel->kelas->nama ?? '';
        preg_match('/\d+/', $kelasNama, $matches);
        $kelasNumber = !empty($matches) ? (int)$matches[0] : 0;

        // Jika kelas 1-9, jurusan harus kosong
        if ($kelasNumber >= 1 && $kelasNumber <= 9) {
            $validated['jurusan'] = null;
        } elseif ($kelasNumber >= 10 && $kelasNumber <= 12) {
            // Jika kelas 10-12, jurusan harus diisi (tidak boleh kosong)
            if (empty($validated['jurusan'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jurusan' => 'Jurusan harus dipilih untuk kelas 10-12.']);
            }
        }

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
