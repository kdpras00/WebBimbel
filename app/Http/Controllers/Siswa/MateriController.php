<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get kelas and jurusan from pivot table
        $kelasSiswa = $user->kelasSiswa()->withPivot('jurusan')->get();
        
        $query = Materi::whereHas('mapel.kelas', function($q) use ($kelasSiswa) {
            $kelasIds = $kelasSiswa->pluck('id');
            $q->whereIn('kelas.id', $kelasIds);
        })
        ->with(['mapel.kelas', 'pengajar']);

        // Filter by kelas and jurusan
        $query->where(function($q) use ($kelasSiswa) {
            foreach ($kelasSiswa as $kelas) {
                $kelasId = $kelas->id;
                $jurusanSiswa = $kelas->pivot->jurusan;
                
                $q->orWhere(function($subQ) use ($kelasId, $jurusanSiswa) {
                    $subQ->whereHas('mapel', function($mapelQ) use ($kelasId) {
                        $mapelQ->where('kelas_id', $kelasId);
                    });
                    
                    // If siswa has jurusan, filter by jurusan match or null
                    // If siswa has no jurusan (kelas 1-6), only show materi with null jurusan
                    if ($jurusanSiswa) {
                        $subQ->where(function($jurusanQ) use ($jurusanSiswa) {
                            $jurusanQ->where('jurusan', $jurusanSiswa)
                                     ->orWhereNull('jurusan');
                        });
                    } else {
                        $subQ->whereNull('jurusan');
                    }
                });
            }
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('mapel', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('mapel.kelas', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $materi = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('siswa.materi.index', compact('materi'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        // Get kelas and jurusan from pivot table
        $kelasSiswa = $user->kelasSiswa()->withPivot('jurusan')->get();
        
        $materi = Materi::whereHas('mapel.kelas', function($q) use ($kelasSiswa) {
            $kelasIds = $kelasSiswa->pluck('id');
            $q->whereIn('kelas.id', $kelasIds);
        })
        ->where(function($q) use ($kelasSiswa) {
            foreach ($kelasSiswa as $kelas) {
                $kelasId = $kelas->id;
                $jurusanSiswa = $kelas->pivot->jurusan;
                
                $q->orWhere(function($subQ) use ($kelasId, $jurusanSiswa) {
                    $subQ->whereHas('mapel', function($mapelQ) use ($kelasId) {
                        $mapelQ->where('kelas_id', $kelasId);
                    });
                    
                    if ($jurusanSiswa) {
                        $subQ->where(function($jurusanQ) use ($jurusanSiswa) {
                            $jurusanQ->where('jurusan', $jurusanSiswa)
                                     ->orWhereNull('jurusan');
                        });
                    } else {
                        $subQ->whereNull('jurusan');
                    }
                });
            }
        })
        ->with(['mapel.kelas', 'pengajar'])
        ->findOrFail($id);

        return view('siswa.materi.show', compact('materi'));
    }
}
