@extends('layouts.app')

@section('title', 'Hasil Belajar Siswa')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Hasil Belajar Siswa</h1>
    <p class="mt-2 text-gray-100">Analisis performa siswa berdasarkan hasil quiz</p>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-700">Total Quiz Dikerjakan</p>
        <p class="text-2xl font-bold text-black">{{ $stats['total_quiz_dikerjakan'] }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-700">Rata-rata Nilai</p>
        <p class="text-2xl font-bold text-black">{{ number_format($stats['rata_rata_nilai'], 1) }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-700">Siswa Terbaik</p>
        <p class="text-lg font-bold text-black">
            @if($stats['siswa_terbaik'])
                {{ $stats['siswa_terbaik']->siswa->name }} ({{ number_format($stats['siswa_terbaik']->avg_nilai, 1) }})
            @else
                -
            @endif
        </p>
    </div>
</div>

<!-- Results Table -->
<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-black">Daftar Hasil Quiz</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black whitespace-nowrap">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Siswa</th>
                    <th scope="col" class="px-6 py-3">Quiz</th>
                    <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                    <th scope="col" class="px-6 py-3">Nilai</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-black">{{ $result->siswa->name }}</td>
                        <td class="px-6 py-4 text-black">{{ $result->quiz->judul }}</td>
                        <td class="px-6 py-4 text-black">{{ $result->quiz->mapel->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded 
                                @if($result->nilai >= 80) bg-green-100 text-green-800
                                @elseif($result->nilai >= 60) bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $result->nilai }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $kkm = $result->quiz->mapel->kkm ?? 70;
                                $lulus = $result->nilai >= $kkm;
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $lulus ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $lulus ? 'LULUS' : 'TIDAK LULUS' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-black">{{ $result->created_at->format('d M Y H:i') }}</td>
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

