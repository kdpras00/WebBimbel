@extends('layouts.app')

@section('title', 'Dashboard Pengajar')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Dashboard Pengajar</h1>
    <p class="mt-2 text-gray-100">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

@if(isset($informasi) && $informasi)
    <div class="mb-8 relative overflow-hidden bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
        <!-- Decorative Gradient Bar -->
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-500 to-indigo-600"></div>
        
        <div class="p-5 pl-8 flex items-start gap-4">
            <!-- Icon Box -->
            <div class="shrink-0 p-3 bg-blue-50 text-blue-600 rounded-xl  transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
            </div>
            
            <!-- Content -->
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide">
                        Informasi Terbaru
                    </span>
                    <span class="text-xs text-slate-400 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ \Carbon\Carbon::parse($informasi->tanggal_mulai)->format('d M Y') }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors">
                    {{ $informasi->judul }}
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    {{ $informasi->deskripsi }}
                </p>
            </div>
        </div>
    </div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">
    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-blue-50 rounded-xl">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Materi</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_materi'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-green-50 rounded-xl">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Quiz</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_quiz'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-purple-50 rounded-xl">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Quiz Published</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_quiz_published'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-50 rounded-xl">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Siswa Aktif</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stats['total_siswa_mengerjakan'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Materi -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="rounded-2xl bg-white shadow-sm border border-slate-100">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-800">Materi Terbaru</h2>
        </div>
        <div class="p-6">
            @forelse($recentMateri as $materi)
                <div class="mb-4 pb-4 border-b border-slate-100 last:border-0">
                    <h3 class="font-medium text-slate-800 mb-1">{{ $materi->judul }}</h3>
                    <p class="text-sm text-slate-500">{{ $materi->mapel->nama }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $materi->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-slate-500 text-center py-4">Belum ada materi</p>
            @endforelse
            <a href="{{ route('pengajar.materi.index') }}" 
               class="mt-4 inline-flex items-center text-sm text-blue-600 hover:underline font-medium">
                Lihat Semua Materi →
            </a>
        </div>
    </div>

    <!-- Recent Quiz -->
    <div class="rounded-2xl bg-white shadow-sm border border-slate-100">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-800">Quiz Terbaru</h2>
        </div>
        <div class="p-6">
            @forelse($recentQuiz as $quiz)
                <div class="mb-4 pb-4 border-b border-slate-100 last:border-0">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-medium text-slate-800">{{ $quiz->judul }}</h3>
                        @if($quiz->is_published)
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                Published
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600">
                                Draft
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500">{{ $quiz->mapel->nama }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $quiz->created_at->format('d M Y') }}</p>
                </div>
            @empty
                <p class="text-slate-500 text-center py-4">Belum ada quiz</p>
            @endforelse
            <a href="{{ route('pengajar.quiz.index') }}" 
               class="mt-4 inline-flex items-center text-sm text-blue-600 hover:underline font-medium">
                Lihat Semua Quiz →
            </a>
        </div>
    </div>
</div>
@endsection

