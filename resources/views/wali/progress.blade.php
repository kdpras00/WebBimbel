@extends('layouts.app')

@section('title', 'Perkembangan Anak')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Perkembangan Anak</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Lihat grafik perkembangan hasil belajar anak Anda</p>
</div>

@if(count($progressData) > 0)
    @foreach($progressData as $data)
        <div class="mb-8 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $data['anak']->name }}</h2>
            
            @if($data['results']->count() > 0)
                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    @php
                        $avgScore = $data['results']->avg('nilai');
                        $totalQuiz = $data['results']->count();
                        $bestScore = $data['results']->max('nilai');
                    @endphp
                    
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgScore, 1) }}</p>
                    </div>
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Quiz</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalQuiz }}</p>
                    </div>
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Nilai Tertinggi</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bestScore }}</p>
                    </div>
                </div>

                <!-- Progress Chart (Simple Table) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Quiz</th>
                                <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                                <th scope="col" class="px-6 py-3">Nilai</th>
                                <th scope="col" class="px-6 py-3">Jawaban Benar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['results'] as $result)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $result->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ $result->quiz->judul }}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">Belum ada hasil quiz untuk {{ $data['anak']->name }}</p>
            @endif
        </div>
    @endforeach
@else
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-12 text-center">
        <p class="text-gray-500 dark:text-gray-400">Belum ada data anak yang terdaftar</p>
    </div>
@endif
@endsection

