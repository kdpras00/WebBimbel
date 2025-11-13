@extends('layouts.app')

@section('title', $materi->judul)

@section('content')
<div class="mb-6">
    <a href="{{ route('siswa.materi.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
        ← Kembali ke Daftar Materi
    </a>
    <h1 class="text-3xl font-bold text-white">{{ $materi->judul }}</h1>
    <div class="mt-2 flex items-center gap-4 text-sm text-gray-100">
        <span>{{ $materi->mapel->kelas->nama }} - {{ $materi->mapel->nama }}</span>
        <span>•</span>
        <span>{{ $materi->pengajar->name }}</span>
    </div>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    @if($materi->deskripsi)
        <div class="mb-6">
            <p class="text-gray-700">{{ $materi->deskripsi }}</p>
        </div>
    @endif

    @if($materi->tipe == 'teks')
        <div class="prose max-w-none">
            {!! nl2br(e($materi->konten)) !!}
        </div>
    @elseif($materi->tipe == 'pdf')
        <div class="mb-4">
            <iframe src="{{ Storage::url($materi->file_path) }}" class="w-full h-screen border rounded-lg"></iframe>
        </div>
        <a href="{{ Storage::url($materi->file_path) }}" target="_blank" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
            Download PDF
        </a>
    @elseif($materi->tipe == 'video')
        <div class="mb-6 flex justify-center">
            <div class="w-full max-w-3xl rounded-xl shadow-xl overflow-hidden bg-black">
                <video
                    controls
                    class="w-full h-full"
                    style="aspect-ratio: 16 / 9;"
                >
                    <source src="{{ Storage::url($materi->file_path) }}" type="video/mp4">
                    Browser Anda tidak mendukung video tag.
                </video>
            </div>
        </div>
        <a href="{{ Storage::url($materi->file_path) }}" target="_blank" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Download Video
        </a>
    @endif
</div>
@endsection

