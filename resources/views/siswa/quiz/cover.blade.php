@extends('layouts.app')

@section('title', $quiz->judul)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('siswa.quiz.index') }}" class="inline-flex items-center text-white hover:text-blue-100 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Quiz
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        <!-- Top Decoration (Mobile only) -->
        <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500 md:hidden"></div>
        
        <!-- Left Side: Exam Details -->
        <div class="w-full md:w-1/3 bg-slate-50 p-8 border-r border-slate-200 flex flex-col justify-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="relative z-10 text-center">
                <div class="w-24 h-24 bg-white rounded-2xl shadow-lg mx-auto mb-6 flex items-center justify-center text-blue-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-slate-800 mb-2">{{ $quiz->judul }}</h1>
                <p class="text-slate-500 text-sm mb-8">{{ $quiz->mapel->nama }} • {{ $quiz->mapel->kelas->nama }}</p>

                <div class="space-y-4 text-left">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                        <div class="text-xs text-slate-400 uppercase tracking-wider">Durasi</div>
                        <div class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $quiz->durasi ?? '∞' }} Menit
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                        <div class="text-xs text-slate-400 uppercase tracking-wider">Soal</div>
                        <div class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            {{ $quiz->questions->count() }} Butir
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 shadow-sm flex items-center justify-between">
                        <div class="text-xs text-yellow-600 uppercase tracking-wider">KKM</div>
                        <div class="font-bold text-yellow-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $quiz->mapel->kkm }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Instructions -->
        <div class="w-full md:w-2/3 p-8 flex flex-col bg-white relative">
             <!-- Top Decoration (Desktop) -->
             <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500 hidden md:block"></div>

            <div class="mb-6 mt-4">
                <h3 class="text-lg font-bold text-slate-800 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    Tata Tertib & Petunjuk Pengerjaan
                </h3>
                
                @if($quiz->deskripsi)
                    <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 text-blue-800 text-sm">
                        {{ $quiz->deskripsi }}
                    </div>
                @endif

                <div class="space-y-4">
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100 transition-colors">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white text-blue-600 border border-blue-100 flex items-center justify-center font-bold shadow-sm text-lg">1</div>
                        <div>
                            <h4 class="font-semibold text-slate-800">Waktu Pengerjaan</h4>
                            <p class="text-sm text-slate-500 mt-1">Waktu akan otomatis berjalan mundur saat Anda menekan tombol "Mulai Kerjakan". Ujian akan tertutup otomatis jika waktu habis.</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100 transition-colors">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white text-blue-600 border border-blue-100 flex items-center justify-center font-bold shadow-sm text-lg">2</div>
                        <div>
                            <h4 class="font-semibold text-slate-800">Integritas Ujian</h4>
                            <p class="text-sm text-slate-500 mt-1">Dilarang membuka tab lain, aplikasi lain, atau melakukan kecurangan. Sistem memantau aktivitas layar Anda.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100 transition-colors">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white text-blue-600 border border-blue-100 flex items-center justify-center font-bold shadow-sm text-lg">3</div>
                        <div>
                            <h4 class="font-semibold text-slate-800">Submit Jawaban</h4>
                            <p class="text-sm text-slate-500 mt-1">Periksa kembali seluruh jawaban sebelum melakukan Submit. Jawaban yang sudah disubmit tidak dapat diubah kembali.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-8 border-t border-slate-100">
                <form action="{{ route('siswa.quiz.start', $quiz->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-8 py-4 text-base font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-3">
                        <span>Saya Mengerti & Mulai Kerjakan</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
