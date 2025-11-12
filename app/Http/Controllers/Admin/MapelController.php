<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = Mapel::with('kelas')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.mapel.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Mapel::create($validated);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.mapel.edit', compact('mapel', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $mapel->update($validated);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil diupdate');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus');
    }
}
