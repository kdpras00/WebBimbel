<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelasIds = $user->kelasSiswa->pluck('id');

        $materi = Materi::whereHas('mapel.kelas', function($query) use ($kelasIds) {
            $query->whereIn('kelas.id', $kelasIds);
        })
        ->with(['mapel.kelas', 'pengajar'])
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('siswa.materi.index', compact('materi'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelasIds = $user->kelasSiswa->pluck('id');

        $materi = Materi::whereHas('mapel.kelas', function($query) use ($kelasIds) {
            $query->whereIn('kelas.id', $kelasIds);
        })
        ->with(['mapel.kelas', 'pengajar'])
        ->findOrFail($id);

        return view('siswa.materi.show', compact('materi'));
    }
}
