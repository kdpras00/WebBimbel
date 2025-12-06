@extends('layouts.app')

@section('title', 'Detail Performa Pengajar')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-blue-100 mb-2">
                <a href="{{ route('owner.teachers.index') }}" class="hover:text-white transition-colors">Performa Pengajar</a>
                <span>/</span>
                <span>Detail</span>
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $teacher->name }}</h1>
            <p class="mt-2 text-blue-100">{{ $teacher->email }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Quiz Dibuat</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $teacher->quizzes->count() }}</h3>
        </div>
        <!-- Average Score -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Rata-rata Nilai Siswa</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ number_format($avg_score, 1) }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Students Taught -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Siswa Diajar</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $total_students_taught }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Riwayat Quiz & Ujian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Judul Quiz</th>
                        <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Partisipan</th>
                        <th scope="col" class="px-6 py-3 text-center">Rata-rata Nilai</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($teacher->quizzes as $quiz)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $quiz->judul }}</td>
                            <td class="px-6 py-4">{{ $quiz->mapel->nama }}</td>
                            <td class="px-6 py-4 text-center">{{ $quiz->results_count }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($quiz->results_count > 0)
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        {{ $quiz->results_avg_nilai >= 80 ? 'bg-green-100 text-green-700' : 
                                           ($quiz->results_avg_nilai >= 70 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ number_format($quiz->results_avg_nilai, 1) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('owner.quizzes.show', $quiz->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada quiz yang dibuat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
