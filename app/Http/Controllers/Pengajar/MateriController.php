<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $materi = Materi::where('pengajar_id', $user->id)
            ->with('mapel.kelas')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengajar.materi.index', compact('materi'));
    }

    public function create()
    {
        $user = Auth::user();
        // Ambil mapel yang diajar pengajar melalui relasi pivot
        $mapel = $user->mapelDiajar()
            ->with('kelas')
            ->get();

        return view('pengajar.materi.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:pdf,video,teks',
            'file_path' => 'nullable|file|mimes:pdf,mp4,avi,mov|max:10240',
            'konten' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
        ]);

        $validated['pengajar_id'] = Auth::id();

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('materi', 'public');
        }

        Materi::create($validated);

        return redirect()->route('pengajar.materi.index')
            ->with('success', 'Materi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $materi = Materi::where('pengajar_id', Auth::id())->findOrFail($id);
        $user = Auth::user();
        // Ambil mapel yang diajar pengajar melalui relasi pivot
        $mapel = $user->mapelDiajar()
            ->with('kelas')
            ->get();

        return view('pengajar.materi.edit', compact('materi', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::where('pengajar_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:pdf,video,teks',
            'file_path' => 'nullable|file|mimes:pdf,mp4,avi,mov|max:10240',
            'konten' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
        ]);

        if ($request->hasFile('file_path')) {
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('materi', 'public');
        }

        $materi->update($validated);

        return redirect()->route('pengajar.materi.index')
            ->with('success', 'Materi berhasil diupdate');
    }

    public function destroy($id)
    {
        $materi = Materi::where('pengajar_id', Auth::id())->findOrFail($id);
        
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }
        
        $materi->delete();

        return redirect()->route('pengajar.materi.index')
            ->with('success', 'Materi berhasil dihapus');
    }
}
