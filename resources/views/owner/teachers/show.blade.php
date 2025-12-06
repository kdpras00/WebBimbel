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
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Pengerjaan Siswa</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $total_results }}</h3>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <p class="text-sm font-medium text-slate-500 mb-1">Rata-rata Nilai Siswa</p>
            <h3 class="text-3xl font-bold {{ $avg_score >= 80 ? 'text-green-600' : ($avg_score >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                {{ number_format($avg_score, 1) }}
            </h3>
        </div>
    </div>

    <!-- Quiz Performance Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Daftar Quiz</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Judul Quiz</th>
                        <th scope="col" class="px-6 py-3">Matapelajaran</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Percobaan</th>
                        <th scope="col" class="px-6 py-3 text-center">Rata-rata Nilai</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($teacher->quizzes as $quiz)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $quiz->judul }}</td>
                            <td class="px-6 py-4">{{ $quiz->mapel->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ $quiz->results_count }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $quiz->results_avg_nilai >= 80 ? 'bg-green-100 text-green-700' : 
                                       ($quiz->results_avg_nilai >= 70 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ number_format($quiz->results_avg_nilai, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $quiz->is_published ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $quiz->is_published ? 'Published' : 'Draft' }}
                                </span>
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
