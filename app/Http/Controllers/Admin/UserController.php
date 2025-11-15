<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $walis = User::where('role', 'wali')->get();
        $kelas = Kelas::orderBy('nama')->get();
        $jurusanOptions = ['IPA', 'IPS'];
        return view('admin.users.create', compact('walis', 'kelas', 'jurusanOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,pengajar,siswa,wali',
            'wali_id' => 'nullable|exists:users,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'jurusan' => 'nullable|string|max:50|in:IPA,IPS',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Handle kelas dan jurusan untuk siswa
        $kelasId = null;
        $jurusan = null;
        
        if ($validated['role'] == 'siswa' && !empty($validated['kelas_id'])) {
            $kelasId = $validated['kelas_id'];
            $kelas = Kelas::findOrFail($kelasId);
            
            // Extract kelas number untuk validasi jurusan
            preg_match('/\d+/', $kelas->nama, $matches);
            $kelasNumber = !empty($matches) ? (int)$matches[0] : 0;
            
            // Jika kelas 1-9, jurusan harus null
            if ($kelasNumber >= 1 && $kelasNumber <= 9) {
                $jurusan = null;
            } elseif ($kelasNumber >= 10 && $kelasNumber <= 12) {
                // Jika kelas 10-12, jurusan harus diisi
                if (empty($validated['jurusan'])) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['jurusan' => 'Jurusan harus dipilih untuk kelas 10-12.']);
                }
                $jurusan = $validated['jurusan'];
            }
        }

        // Hapus kelas_id dan jurusan dari validated karena tidak ada di tabel users
        unset($validated['kelas_id'], $validated['jurusan']);

        $user = User::create($validated);

        // Assign siswa ke kelas jika role siswa
        if ($validated['role'] == 'siswa' && $kelasId) {
            DB::table('kelas_siswa')->updateOrInsert(
                [
                    'kelas_id' => $kelasId,
                    'siswa_id' => $user->id,
                ],
                [
                    'jurusan' => $jurusan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $walis = User::where('role', 'wali')->get();
        $kelas = Kelas::orderBy('nama')->get();
        $jurusanOptions = ['IPA', 'IPS'];
        
        // Get kelas dan jurusan siswa saat ini
        $kelasSiswa = null;
        $jurusanSiswa = null;
        if ($user->role == 'siswa') {
            $kelasSiswaData = DB::table('kelas_siswa')
                ->where('siswa_id', $user->id)
                ->first();
            if ($kelasSiswaData) {
                $kelasSiswa = $kelasSiswaData->kelas_id;
                $jurusanSiswa = $kelasSiswaData->jurusan;
            }
        }
        
        return view('admin.users.edit', compact('user', 'walis', 'kelas', 'jurusanOptions', 'kelasSiswa', 'jurusanSiswa'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,pengajar,siswa,wali',
            'wali_id' => 'nullable|exists:users,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'jurusan' => 'nullable|string|max:50|in:IPA,IPS',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle kelas dan jurusan untuk siswa
        $kelasId = null;
        $jurusan = null;
        
        if ($validated['role'] == 'siswa') {
            // Hapus semua kelas siswa yang lama
            DB::table('kelas_siswa')->where('siswa_id', $user->id)->delete();
            
            if (!empty($validated['kelas_id'])) {
                $kelasId = $validated['kelas_id'];
                $kelas = Kelas::findOrFail($kelasId);
                
                // Extract kelas number untuk validasi jurusan
                preg_match('/\d+/', $kelas->nama, $matches);
                $kelasNumber = !empty($matches) ? (int)$matches[0] : 0;
                
                // Jika kelas 1-9, jurusan harus null
                if ($kelasNumber >= 1 && $kelasNumber <= 9) {
                    $jurusan = null;
                } elseif ($kelasNumber >= 10 && $kelasNumber <= 12) {
                    // Jika kelas 10-12, jurusan harus diisi
                    if (empty($validated['jurusan'])) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['jurusan' => 'Jurusan harus dipilih untuk kelas 10-12.']);
                    }
                    $jurusan = $validated['jurusan'];
                }
                
                // Assign siswa ke kelas baru
                DB::table('kelas_siswa')->insert([
                    'kelas_id' => $kelasId,
                    'siswa_id' => $user->id,
                    'jurusan' => $jurusan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Jika role bukan siswa, hapus semua kelas siswa
            DB::table('kelas_siswa')->where('siswa_id', $user->id)->delete();
        }

        // Hapus kelas_id dan jurusan dari validated karena tidak ada di tabel users
        unset($validated['kelas_id'], $validated['jurusan']);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
