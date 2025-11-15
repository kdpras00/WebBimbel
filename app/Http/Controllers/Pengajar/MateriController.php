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

        $jurusanOptions = ['IPA', 'IPS'];

        return view('pengajar.materi.create', compact('mapel', 'jurusanOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:pdf,video,teks',
            'file_path' => 'nullable|file|max:10240',
            'konten' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
            'jurusan' => 'nullable|string|max:50',
        ]);

        // Validasi file sesuai tipe
        if ($validated['tipe'] == 'pdf') {
            $request->validate([
                'file_path' => 'required|file|mimes:pdf|max:10240',
            ]);
        } elseif ($validated['tipe'] == 'video') {
            $request->validate([
                'file_path' => 'required|file|mimes:mp4,avi,mov|max:10240',
            ]);
        } elseif ($validated['tipe'] == 'teks') {
            // Untuk teks, file tidak diperlukan
            if ($request->hasFile('file_path')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file_path' => 'Tipe teks tidak memerlukan file.']);
            }
        }

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

        $jurusanOptions = ['IPA', 'IPS'];

        return view('pengajar.materi.edit', compact('materi', 'mapel', 'jurusanOptions'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::where('pengajar_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:pdf,video,teks',
            'file_path' => 'nullable|file|max:10240',
            'konten' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
            'jurusan' => 'nullable|string|max:50',
        ]);

        // Validasi file sesuai tipe (hanya jika file diupload)
        if ($request->hasFile('file_path')) {
            if ($validated['tipe'] == 'pdf') {
                $request->validate([
                    'file_path' => 'required|file|mimes:pdf|max:10240',
                ]);
            } elseif ($validated['tipe'] == 'video') {
                $request->validate([
                    'file_path' => 'required|file|mimes:mp4,avi,mov|max:10240',
                ]);
            } elseif ($validated['tipe'] == 'teks') {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file_path' => 'Tipe teks tidak memerlukan file.']);
            }
        } else {
            // Jika tidak ada file baru diupload, pastikan tipe sesuai dengan file yang sudah ada
            if ($validated['tipe'] == 'pdf' && $materi->file_path && !str_ends_with($materi->file_path, '.pdf')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['tipe' => 'File yang ada bukan PDF. Silakan upload file PDF baru atau ubah tipe.']);
            } elseif ($validated['tipe'] == 'video' && $materi->file_path) {
                $videoExtensions = ['.mp4', '.avi', '.mov'];
                $isVideo = false;
                foreach ($videoExtensions as $ext) {
                    if (str_ends_with(strtolower($materi->file_path), $ext)) {
                        $isVideo = true;
                        break;
                    }
                }
                if (!$isVideo) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['tipe' => 'File yang ada bukan video. Silakan upload file video baru atau ubah tipe.']);
                }
            }
        }

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
