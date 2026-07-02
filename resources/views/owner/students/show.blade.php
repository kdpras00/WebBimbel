@extends('layouts.app')

@section('title', 'Detail Progress Siswa')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('owner.students.index') }}" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">Laporan Progress Siswa</h1>
            </div>
            <p class="text-white ml-7">Statistik dan riwayat pengerjaan quiz</p>
        </div>
        <a href="{{ route('owner.students.pdf', $student->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download Laporan (PDF)
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Quiz</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_quiz'] }}</h3>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Rata-rata Nilai</p>
            <h3 class="text-3xl font-bold {{ $stats['avg_score'] >= 80 ? 'text-green-600' : ($stats['avg_score'] >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                {{ number_format($stats['avg_score'], 1) }}
            </h3>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Nilai Tertinggi</p>
            <h3 class="text-3xl font-bold text-green-600">{{ $stats['highest_score'] }}</h3>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Nilai Terendah</p>
            <h3 class="text-3xl font-bold text-red-600">{{ $stats['lowest_score'] }}</h3>
        </div>
    </div>

    <!-- Quiz History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Riwayat Quiz</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-3">Judul Quiz</th>
                        <th scope="col" class="px-6 py-3">Pengajar</th>
                        <th scope="col" class="px-6 py-3 text-center">Nilai</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($student->quizResults as $result)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $result->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $result->quiz->mapel->nama ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $result->quiz->judul }}</td>
                            <td class="px-6 py-4">{{ $result->quiz->pengajar->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-bold {{ $result->nilai >= ($result->quiz->mapel->kkm ?? 70) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $result->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $result->nilai >= ($result->quiz->mapel->kkm ?? 70) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $result->nilai >= ($result->quiz->mapel->kkm ?? 70) ? 'Lulus' : 'Remedial' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat pengerjaan quiz</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
