<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount(['siswa', 'pengajar'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_number' => 'nullable|integer|min:1|max:12',
            'jurusan' => 'nullable|array',
            'jurusan.*' => 'in:IPA,IPS',
        ]);

        // Jika kelas_number ada, gunakan itu untuk set nama
        if ($request->filled('kelas_number')) {
            $validated['nama'] = 'Kelas ' . $request->kelas_number;
        }

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kelas = Kelas::with(['mapel.pengajar', 'siswa'])->findOrFail($id);
        $pengajar = \App\Models\User::where('role', 'pengajar')->get();
        $siswa = \App\Models\User::where('role', 'siswa')->get();
        $jurusanOptions = ['IPA', 'IPS'];
        
        // Extract kelas number untuk menentukan apakah perlu jurusan
        preg_match('/\d+/', $kelas->nama, $matches);
        $kelasNumber = !empty($matches) ? (int)$matches[0] : 0;
        $showJurusan = $kelasNumber >= 10 && $kelasNumber <= 12;
        
        return view('admin.kelas.edit', compact('kelas', 'pengajar', 'siswa', 'jurusanOptions', 'showJurusan'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_number' => 'nullable|integer|min:1|max:12',
            'jurusan' => 'nullable|array',
            'jurusan.*' => 'in:IPA,IPS',
        ]);

        // Jika kelas_number ada, gunakan itu untuk set nama
        if ($request->filled('kelas_number')) {
            $validated['nama'] = 'Kelas ' . $request->kelas_number;
        }

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus');
    }

    public function assignPengajar(Request $request, $id)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'pengajar_id' => 'required|exists:users,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $mapel = \App\Models\Mapel::findOrFail($validated['mapel_id']);
        
        // Pastikan mapel ini milik kelas yang sama
        if ($mapel->kelas_id != $kelas->id) {
            return redirect()->back()
                ->with('error', 'Mapel tidak sesuai dengan kelas yang dipilih');
        }

        // Pastikan user adalah pengajar
        $pengajar = \App\Models\User::findOrFail($validated['pengajar_id']);
        if ($pengajar->role != 'pengajar') {
            return redirect()->back()
                ->with('error', 'User yang dipilih bukan pengajar');
        }

        // Assign pengajar ke mapel di kelas ini
        DB::table('kelas_pengajar')->updateOrInsert(
            [
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'pengajar_id' => $pengajar->id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->back()
            ->with('success', 'Pengajar berhasil di-assign ke mapel');
    }

    public function unassignPengajar(Request $request, $id)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'pengajar_id' => 'required|exists:users,id',
        ]);

        DB::table('kelas_pengajar')
            ->where('kelas_id', $id)
            ->where('mapel_id', $validated['mapel_id'])
            ->where('pengajar_id', $validated['pengajar_id'])
            ->delete();

        return redirect()->back()
            ->with('success', 'Pengajar berhasil di-unassign dari mapel');
    }

    public function assignSiswa(Request $request, $id)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'jurusan' => 'nullable|string|max:50|in:IPA,IPS',
        ]);

        $kelas = Kelas::findOrFail($id);
        
        // Pastikan user adalah siswa
        $siswa = \App\Models\User::findOrFail($validated['siswa_id']);
        if ($siswa->role != 'siswa') {
            return redirect()->back()
                ->with('error', 'User yang dipilih bukan siswa');
        }

        // Extract kelas number untuk validasi jurusan
        preg_match('/\d+/', $kelas->nama, $matches);
        $kelasNumber = !empty($matches) ? (int)$matches[0] : 0;

        // Jika kelas 1-9, jurusan harus null
        if ($kelasNumber >= 1 && $kelasNumber <= 9) {
            $validated['jurusan'] = null;
        } elseif ($kelasNumber >= 10 && $kelasNumber <= 12) {
            // Jika kelas 10-12, jurusan harus diisi
            if (empty($validated['jurusan'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jurusan' => 'Jurusan harus dipilih untuk kelas 10-12.']);
            }
        }

        // Assign siswa ke kelas
        DB::table('kelas_siswa')->updateOrInsert(
            [
                'kelas_id' => $kelas->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'jurusan' => $validated['jurusan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->back()
            ->with('success', 'Siswa berhasil di-assign ke kelas');
    }

    public function unassignSiswa(Request $request, $id)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:users,id',
        ]);

        DB::table('kelas_siswa')
            ->where('kelas_id', $id)
            ->where('siswa_id', $validated['siswa_id'])
            ->delete();

        return redirect()->back()
            ->with('success', 'Siswa berhasil di-unassign dari kelas');
    }
}
