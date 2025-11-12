@extends('layouts.app')

@section('title', 'Progress Belajar')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Progress Belajar</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Lihat perkembangan nilai dan hasil belajar Anda</p>
</div>

@if($results->count() > 0)
    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        @php
            $averageScore = $results->avg('nilai');
            $totalQuiz = $results->count();
            $bestScore = $results->max('nilai');
        @endphp
        
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($averageScore, 1) }}</p>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Quiz</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalQuiz }}</p>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai Tertinggi</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bestScore }}</p>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Riwayat Quiz</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Quiz</th>
                        <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-3">Nilai</th>
                        <th scope="col" class="px-6 py-3">Jawaban Benar</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $result->quiz->judul }}
                            </td>
                            <td class="px-6 py-4">{{ $result->quiz->mapel->nama }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded 
                                    @if($result->nilai >= 80) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($result->nilai >= 60) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @endif">
                                    {{ $result->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</td>
                            <td class="px-6 py-4">{{ $result->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-12 text-center">
        <p class="text-gray-500 dark:text-gray-400">Belum ada hasil quiz. Mulai kerjakan quiz untuk melihat progress Anda!</p>
        <a href="{{ route('siswa.quiz.index') }}" 
           class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Lihat Quiz
        </a>
    </div>
@endif
@endsection

