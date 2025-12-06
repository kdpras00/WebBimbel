<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        $informasi = Informasi::orderBy('tanggal_mulai', 'desc')->get();
        return view('admin.informasi.index', compact('informasi'));
    }

    public function create()
    {
        return view('admin.informasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $informasi = Informasi::create($validated);

        // Notify all users
        $users = \App\Models\User::all();
        \Illuminate\Support\Facades\Notification::send($users, new \App\Notifications\NewAnnouncement($informasi));

        return redirect()->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $informasi = Informasi::findOrFail($id);
        return view('admin.informasi.edit', compact('informasi'));
    }

    public function update(Request $request, $id)
    {
        $informasi = Informasi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $informasi->update($validated);

        return redirect()->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil diupdate');
    }

    public function destroy($id)
    {
        $informasi = Informasi::findOrFail($id);
        $informasi->delete();

        return redirect()->route('admin.informasi.index')
            ->with('success', 'Informasi berhasil dihapus');
    }
}
