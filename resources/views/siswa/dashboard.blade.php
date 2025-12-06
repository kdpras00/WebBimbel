@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">Dashboard Siswa</h1>
    <p class="mt-2 text-blue-100">Selamat datang kembali, {{ Auth::user()->name }}!</p>
</div>

<!-- Information Section -->
@if(isset($informasi) && $informasi->count() > 0)
    <div class="mb-8 space-y-4">
        @foreach($informasi as $info)
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
                <!-- Decorative Gradient Bar -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-500 to-indigo-600"></div>
                
                <div class="p-5 pl-8 flex items-start gap-4">
                    <!-- Icon Box -->
                    <div class="shrink-0 p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide">
                                Informasi
                            </span>
                            <span class="text-xs text-slate-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $info->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors">
                            {{ $info->judul }}
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            {{ $info->deskripsi }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-50 rounded-xl">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Poin</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalPoin }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-blue-50 rounded-xl">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Quiz</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalQuiz }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-green-50 rounded-xl">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Rata-rata Nilai</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($averageScore, 1) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Score Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-purple-50 rounded-xl">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Nilai Tertinggi</p>
                <p class="text-2xl font-bold text-slate-800">{{ $highestScore ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-red-50 rounded-xl">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Nilai Terendah</p>
                <p class="text-2xl font-bold text-slate-800">{{ $lowestScore ?? 0 }}</p>
            </div>
        </div>
    </div>

    @if($latestResult)
    <div class="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-50 rounded-xl">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Nilai Terbaru</p>
                <p class="text-2xl font-bold text-slate-800">{{ $latestResult->nilai }}</p>
                <p class="text-xs text-slate-400 mt-1 truncate max-w-[150px]">{{ $latestResult->quiz->judul }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Progress Learning Section -->
<div class="mb-6 rounded-2xl bg-white shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-800">Progress Belajar</h2>
        <a href="{{ route('siswa.progress') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-medium transition-colors shadow-sm shadow-blue-200">
            Lihat Riwayat
        </a>
    </div>
    @if(isset($allResults) && $allResults->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-sm text-blue-600 font-medium">Rata-rata Nilai</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($averageScore, 1) }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-sm text-green-600 font-medium">Total Quiz</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalQuiz }}</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <p class="text-sm text-yellow-600 font-medium">Nilai Tertinggi</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $highestScore }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <p class="text-slate-500">Belum ada hasil quiz. Mulai kerjakan quiz untuk melihat progress Anda!</p>
        </div>
    @endif
</div>

<!-- Recent Results -->
<div class="rounded-2xl bg-white shadow-sm border border-slate-100">
    <div class="p-6 border-b border-slate-100">
        <h2 class="text-xl font-bold text-slate-800">Hasil Quiz Terakhir</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">Quiz</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nilai</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentResults as $result)
                    <tr class="bg-white hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $result->quiz->judul }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-700">
                                {{ $result->nilai }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $kkm = $result->quiz->mapel->kkm ?? 70;
                                $lulus = $result->nilai >= $kkm;
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $lulus ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $lulus ? 'LULUS' : 'TIDAK LULUS' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $result->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Belum ada hasil quiz</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

