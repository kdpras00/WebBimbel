@extends('layouts.app')

@section('title', 'Progress Belajar')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Progress Belajar</h1>
    <p class="mt-2 text-gray-100">Lihat perkembangan nilai dan hasil belajar Anda</p>
</div>

@if($results->count() > 0)
    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        @php
            $averageScore = $results->avg('nilai');
            $totalQuiz = $results->count();
            $bestScore = $results->max('nilai');
        @endphp

        <div class="rounded-lg shadow border border-gray-200 p-6 flex flex-col items-center bg-white">
            <p class="text-sm font-medium text-gray-600 mb-1">Rata-rata Nilai</p>
            <p class="text-2xl font-bold text-black">{{ number_format($averageScore, 1) }}</p>
        </div>
        <div class="rounded-lg shadow border border-gray-200 p-6 flex flex-col items-center bg-white">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Quiz</p>
            <p class="text-2xl font-bold text-black">{{ $totalQuiz }}</p>
        </div>
        <div class="rounded-lg shadow border border-gray-200 p-6 flex flex-col items-center bg-white">
            <p class="text-sm font-medium text-gray-600 mb-1">Nilai Tertinggi</p>
            <p class="text-2xl font-bold text-black">{{ $bestScore }}</p>
        </div>
    </div>

    <!-- Results Table -->
    <div class="rounded-lg shadow border border-gray-200 bg-white">
        <div class="p-6 border-b border-gray-200 bg-white">
            <h2 class="text-xl font-semibold text-black">Riwayat Quiz</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-black">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
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
                        <tr class="bg-white border-b last:border-0">
                            <td class="px-6 py-4 font-medium text-black align-middle">
                                {{ $result->quiz->judul }}
                            </td>
                            <td class="px-6 py-4 text-black align-middle">{{ $result->quiz->mapel->nama }}</td>
                            <td class="px-6 py-4 align-middle">
                                <span class="px-2 py-1 text-xs font-medium rounded 
                                    @if($result->nilai >= 80) bg-green-100 text-green-800
                                    @elseif($result->nilai >= 60) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $result->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-black align-middle">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</td>
                            <td class="px-6 py-4 text-black align-middle whitespace-nowrap">{{ $result->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="rounded-lg shadow border border-gray-200 p-12 text-center bg-white">
        <p class="text-gray-500">Belum ada hasil quiz. Mulai kerjakan quiz untuk melihat progress Anda!</p>
        <a href="{{ route('siswa.quiz.index') }}" 
           class="mt-4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Lihat Quiz
        </a>
    </div>
@endif
@endsection

