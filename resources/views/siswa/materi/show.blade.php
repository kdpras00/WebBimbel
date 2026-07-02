@extends('layouts.app')

@section('title', $materi->judul)

@section('content')
<div class="mb-6">
    <a href="{{ route('siswa.materi.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white transition-colors mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Daftar Materi
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
            {!! $materi->konten !!}
        </div>
    @elseif($materi->tipe == 'pdf')
        <div class="mb-4">
            <iframe src="{{ route('materi.file', $materi->id) }}" class="w-full h-screen border rounded-lg"></iframe>
        </div>
        <a href="{{ route('materi.file', $materi->id) }}" target="_blank" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
            Download PDF
        </a>
    @elseif($materi->tipe == 'video')
        <div class="mb-6 flex justify-center">
            <div class="w-full rounded-xl shadow-xl overflow-hidden bg-black" style="max-width: 100%;">
                <video
                    controls
                    class="w-full h-full"
                    style="aspect-ratio: 16 / 9; min-height: 500px; width: 100%;"
                >
                    <source src="{{ route('materi.file', $materi->id) }}" type="video/mp4">
                    Browser Anda tidak mendukung video tag.
                </video>
            </div>
        </div>
        <div class="flex justify-start">
            <a href="{{ route('materi.file', $materi->id) }}" download
               class="inline-flex items-center gap-2 px-6 py-3 text-base font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Video
            </a>
        </div>
    @endif
</div>
@endsection

