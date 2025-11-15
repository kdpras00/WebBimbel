@extends('layouts.app')

@section('title', 'Quiz & Latihan Soal')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Quiz & Latihan Soal</h1>
    <p class="mt-2 text-gray-100">Pilih quiz yang ingin dikerjakan</p>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <form method="GET" action="{{ route('siswa.quiz.index') }}" class="flex gap-2">
        <div class="flex-1 relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari quiz berdasarkan judul, deskripsi, mapel, atau kelas..." 
                   class="w-full px-4 py-3 pl-10 text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <button type="submit" 
                class="px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('siswa.quiz.index') }}" 
               class="px-6 py-3 text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($quizzes as $quiz)
        @php
            $quizResult = $quizResults[$quiz->id] ?? null;
            $attemptCount = $quizResult['count'] ?? 0;
            $maxAttempt = $quizResult['max_attempt'] ?? 0;
            $isCompleted = $attemptCount > 0;
            $canRetry = $attemptCount < 3;
            $latestResult = $quizResult['latest_result'] ?? null;
        @endphp
        <div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                        {{ $quiz->mapel->kelas->nama }}
                    </span>
                    <div class="flex items-center gap-2">
                        @if($isCompleted)
                            <span class="px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-800">
                                Sudah Dikerjakan
                            </span>
                        @endif
                        @if($quiz->is_published)
                            <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">
                                Published
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>
                
                <h3 class="mb-2 text-xl font-bold text-black">{{ $quiz->judul }}</h3>
                @if($quiz->deskripsi)
                    <p class="mb-4 text-sm text-gray-700">{{ Str::limit($quiz->deskripsi, 100) }}</p>
                @endif
                
                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                    <span>{{ $quiz->mapel->nama }}</span>
                    @if($quiz->durasi)
                        <span>⏱️ {{ $quiz->durasi }} menit</span>
                    @endif
                </div>

                @if($isCompleted && $latestResult)
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 font-medium">Nilai Terakhir:</span>
                            <span class="text-lg font-bold text-blue-600">{{ $latestResult->nilai }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-600 mt-1">
                            <span>Attempt: {{ $maxAttempt }}/3</span>
                            <span>{{ $latestResult->jawaban_benar }}/{{ $latestResult->total_soal }} benar</span>
                        </div>
                    </div>
                @endif
                
                @if($quiz->is_published)
                    @if($isCompleted && $canRetry)
                        <a href="{{ route('siswa.quiz.show', $quiz->id) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-orange-600 rounded-lg hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Ulangi Quiz
                        </a>
                    @elseif($isCompleted && !$canRetry)
                        <div class="space-y-2">
                            <a href="{{ route('siswa.quiz.result', $latestResult->id) }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat Hasil
                            </a>
                            <p class="text-xs text-gray-500">Anda sudah mencapai batas maksimal 3 kali attempt</p>
                        </div>
                    @else
                        <a href="{{ route('siswa.quiz.show', $quiz->id) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                            Mulai Quiz
                        </a>
                    @endif
                @else
                    <button disabled 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-gray-400 rounded-lg cursor-not-allowed">
                        Belum Tersedia
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            @if(request('search'))
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="mt-4 text-lg font-medium text-gray-900">Tidak ada hasil ditemukan</p>
                <p class="mt-2 text-sm text-gray-600">Coba cari dengan kata kunci lain atau <a href="{{ route('siswa.quiz.index') }}" class="text-blue-600 hover:text-blue-700">lihat semua quiz</a></p>
            @else
                <p class="text-gray-100">Belum ada quiz yang tersedia</p>
            @endif
        </div>
    @endforelse
</div>
@endsection

