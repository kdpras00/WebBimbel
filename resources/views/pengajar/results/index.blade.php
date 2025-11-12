@extends('layouts.app')

@section('title', 'Hasil Belajar Siswa')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Hasil Belajar Siswa</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Analisis performa siswa berdasarkan hasil quiz</p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Quiz Dikerjakan</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_quiz_dikerjakan'] }}</p>
    </div>
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['rata_rata_nilai'], 1) }}</p>
    </div>
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Siswa Terbaik</p>
        <p class="text-lg font-bold text-gray-900 dark:text-white">
            @if($stats['siswa_terbaik'])
                {{ $stats['siswa_terbaik']->siswa->name }} ({{ number_format($stats['siswa_terbaik']->avg_nilai, 1) }})
            @else
                -
            @endif
        </p>
    </div>
</div>

<!-- Results Table -->
<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Hasil Quiz</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Siswa</th>
                    <th scope="col" class="px-6 py-3">Quiz</th>
                    <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                    <th scope="col" class="px-6 py-3">Nilai</th>
                    <th scope="col" class="px-6 py-3">Jawaban Benar</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $result->siswa->name }}</td>
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
                        <td class="px-6 py-4">{{ $result->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('pengajar.results.show', $result->id) }}" class="text-blue-600 hover:underline mr-3">Detail</a>
                            <a href="{{ route('pengajar.feedback.create', $result->id) }}" class="text-green-600 hover:underline">Feedback</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada hasil quiz</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $results->links() }}
    </div>
</div>
@endsection

