<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_pengajar' => User::where('role', 'pengajar')->count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_wali' => User::where('role', 'wali')->count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => Mapel::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
