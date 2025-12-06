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
        ], [
            'file_path.file' => 'File yang diupload tidak valid',
            'file_path.max' => 'Ukuran file maksimal 10 MB',
            'file_path.uploaded' => 'File gagal diupload. Pastikan ukuran file tidak melebihi 10 MB dan konfigurasi PHP sudah benar.'
        ]);

        // Validasi file sesuai tipe
        if ($validated['tipe'] == 'pdf') {
            $request->validate([
                'file_path' => 'required|file|mimes:pdf|max:10240',
            ], [
                'file_path.mimes' => 'File harus berformat PDF',
                'file_path.max' => 'Ukuran file PDF maksimal 10 MB'
            ]);
        } elseif ($validated['tipe'] == 'video') {
            $request->validate([
                'file_path' => 'required|file|mimes:mp4,avi,mov,quicktime,video/mp4,video/quicktime,video/x-msvideo|max:10240',
            ], [
                'file_path.mimes' => 'File harus berformat video (MP4, AVI, atau MOV)',
                'file_path.max' => 'Ukuran file video maksimal 10 MB'
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
            try {
                $validated['file_path'] = $request->file('file_path')->store('materi', 'public');
                if (!$validated['file_path']) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['file_path' => 'Gagal mengupload file. Pastikan folder storage memiliki permission yang benar.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file_path' => 'Gagal mengupload file: ' . $e->getMessage()]);
            }
        }

        $materi = Materi::create($validated);

        // Notify students in the class
        if ($mapel->kelas && $mapel->kelas->siswa->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($mapel->kelas->siswa, new \App\Notifications\NewContent($materi, 'materi'));
        }

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
        ], [
            'file_path.file' => 'File yang diupload tidak valid',
            'file_path.max' => 'Ukuran file maksimal 10 MB',
            'file_path.uploaded' => 'File gagal diupload. Pastikan ukuran file tidak melebihi 10 MB dan konfigurasi PHP sudah benar.'
        ]);

        // Validasi file sesuai tipe (hanya jika file diupload)
        if ($request->hasFile('file_path')) {
            try {
                if ($validated['tipe'] == 'pdf') {
                    $request->validate([
                        'file_path' => 'required|file|mimes:pdf|max:10240',
                    ], [
                        'file_path.required' => 'File PDF wajib diupload',
                        'file_path.file' => 'File yang diupload tidak valid',
                        'file_path.mimes' => 'File harus berformat PDF',
                        'file_path.max' => 'Ukuran file PDF maksimal 10 MB',
                        'file_path.uploaded' => 'File gagal diupload. Pastikan ukuran file tidak melebihi 10 MB dan format file benar.'
                    ]);
                } elseif ($validated['tipe'] == 'video') {
                    // Validasi dengan extension dan MIME type yang lebih fleksibel
                    $file = $request->file('file_path');
                    
                    if (!$file || !$file->isValid()) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['file_path' => 'File video tidak valid atau gagal diupload. Pastikan file tidak rusak dan ukurannya tidak melebihi 10 MB.']);
                    }
                    
                    $extension = strtolower($file->getClientOriginalExtension());
                    $allowedExtensions = ['mp4', 'avi', 'mov'];
                    
                    if (!in_array($extension, $allowedExtensions)) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['file_path' => 'File harus berformat video (MP4, AVI, atau MOV)']);
                    }
                    
                    $fileSizeKB = $file->getSize() / 1024;
                    if ($fileSizeKB > 10240) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['file_path' => 'Ukuran file video maksimal 10 MB']);
                    }
                } elseif ($validated['tipe'] == 'teks') {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['file_path' => 'Tipe teks tidak memerlukan file.']);
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($e->errors());
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
            try {
                // Hapus file lama jika ada
                if ($materi->file_path) {
                    try {
                        Storage::disk('public')->delete($materi->file_path);
                    } catch (\Exception $e) {
                        // Ignore error jika file lama tidak ditemukan
                    }
                }
                
                // Upload file baru
                $validated['file_path'] = $request->file('file_path')->store('materi', 'public');
                if (!$validated['file_path']) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['file_path' => 'Gagal mengupload file. Pastikan folder storage memiliki permission yang benar.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file_path' => 'Gagal mengupload file: ' . $e->getMessage()]);
            }
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
