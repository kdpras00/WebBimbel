@extends('layouts.app')

@section('title', 'Detail Materi - ' . $materi->judul)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('pengajar.materi.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Materi
        </a>
        <h1 class="text-3xl font-bold text-white">{{ $materi->judul }}</h1>
        <div class="mt-2 flex items-center gap-4 text-sm text-gray-100">
            <span>{{ $materi->mapel->kelas->nama }} - {{ $materi->mapel->nama }}</span>
            <span>•</span>
            <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                {{ strtoupper($materi->tipe) }}
            </span>
            <span>•</span>
            <span>{{ $materi->created_at->format('d M Y') }}</span>
        </div>
    </div>
    <div>
        <a href="{{ route('pengajar.materi.edit', $materi->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Materi
        </a>
    </div>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    @if($materi->deskripsi)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
            <p class="text-gray-700">{{ $materi->deskripsi }}</p>
        </div>
        <hr class="my-6 border-gray-300">
    @endif

    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Konten Materi</h3>
    </div>

    @if($materi->tipe == 'teks')
        <div class="ql-snow bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="ql-editor" style="padding: 0;">
                {!! $materi->konten !!}
            </div>
        </div>
        
        @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
        @endpush
    @elseif($materi->tipe == 'pdf')
        <div class="mb-4">
            <iframe src="{{ route('materi.file', $materi->id) }}" class="w-full h-screen border rounded-lg bg-white"></iframe>
        </div>
        <div class="flex justify-start">
            <a href="{{ route('materi.file', $materi->id) }}" target="_blank" 
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download PDF
            </a>
        </div>
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
