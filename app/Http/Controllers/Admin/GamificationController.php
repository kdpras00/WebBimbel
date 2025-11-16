<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GamificationSetting;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    public function index()
    {
        $settings = GamificationSetting::orderBy('nilai_min', 'desc')->get();
        return view('admin.gamification.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_aturan' => 'required|string|max:255',
            'nilai_min' => 'nullable|integer|min:0|max:100',
            'nilai_max' => 'nullable|integer|min:0|max:100',
            'poin' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        GamificationSetting::create($validated);

        return redirect()->route('admin.gamification.index')
            ->with('success', 'Aturan gamifikasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $setting = GamificationSetting::findOrFail($id);
        return view('admin.gamification.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = GamificationSetting::findOrFail($id);

        $validated = $request->validate([
            'nama_aturan' => 'required|string|max:255',
            'nilai_min' => 'nullable|integer|min:0|max:100',
            'nilai_max' => 'nullable|integer|min:0|max:100',
            'poin' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $setting->update($validated);

        return redirect()->route('admin.gamification.index')
            ->with('success', 'Aturan gamifikasi berhasil diupdate');
    }

    public function destroy($id)
    {
        $setting = GamificationSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('admin.gamification.index')
            ->with('success', 'Aturan gamifikasi berhasil dihapus');
    }
}
