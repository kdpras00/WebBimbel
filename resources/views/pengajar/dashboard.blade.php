@extends('layouts.app')

@section('title', 'Dashboard Pengajar')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Pengajar</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Materi</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_materi'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg dark:bg-green-900">
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Quiz</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_quiz'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg dark:bg-purple-900">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Quiz Published</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_quiz_published'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Siswa Aktif</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_siswa_mengerjakan'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Materi -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Materi Terbaru</h2>
        </div>
        <div class="p-6">
            @forelse($recentMateri as $materi)
                <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                    <h3 class="font-medium text-gray-900 dark:text-white mb-1">{{ $materi->judul }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $materi->mapel->nama }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $materi->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Belum ada materi</p>
            @endforelse
            <a href="{{ route('pengajar.materi.index') }}" 
               class="mt-4 inline-flex items-center text-sm text-blue-600 hover:underline">
                Lihat Semua Materi →
            </a>
        </div>
    </div>

    <!-- Recent Quiz -->
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Quiz Terbaru</h2>
        </div>
        <div class="p-6">
            @forelse($recentQuiz as $quiz)
                <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $quiz->judul }}</h3>
                        @if($quiz->is_published)
                            <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                Published
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                Draft
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->mapel->nama }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $quiz->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Belum ada quiz</p>
            @endforelse
            <a href="{{ route('pengajar.quiz.index') }}" 
               class="mt-4 inline-flex items-center text-sm text-blue-600 hover:underline">
                Lihat Semua Quiz →
            </a>
        </div>
    </div>
</div>
@endsection

