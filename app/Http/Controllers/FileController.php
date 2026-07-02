<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * Menampilkan atau mengunduh file materi secara aman
     */
    public function showMateri(Materi $materi)
    {
        // Pastikan file ada
        if (!$materi->file_path || !Storage::exists($materi->file_path)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        // Return file sebagai response (bisa untuk iframe, video, atau download)
        return Storage::response($materi->file_path);
    }
}
