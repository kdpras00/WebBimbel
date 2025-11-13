@extends('layouts.app')

@section('title', 'Perkembangan Anak')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Perkembangan Anak</h1>
    <p class="mt-2 text-gray-100">Lihat grafik perkembangan hasil belajar anak Anda</p>
</div>

@if(count($progressData) > 0)
    @foreach($progressData as $data)
        <div class="mb-8 rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <h2 class="text-2xl font-bold text-black mb-6>{{ $data['anak']->name }}</h2>
            
            @if($data['results']->count() > 0)
                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6>
                    @php
                        $avgScore = $data['results']->avg('nilai');
                        $totalQuiz = $data['results']->count();
                        $bestScore = $data['results']->max('nilai');
                    @endphp
                    
                    <div class="p-4 bg-blue-50/20 rounded-lg">
                        <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                        <p class="text-2xl font-bold text-black">{{ number_format($avgScore, 1) }}</p>
                    </div>
                    <div class="p-4 bg-green-50/20 rounded-lg">
                        <p class="text-sm text-gray-600">Total Quiz</p>
                        <p class="text-2xl font-bold text-black">{{ $totalQuiz }}</p>
                    </div>
                    <div class="p-4 bg-yellow-50/20 rounded-lg">
                        <p class="text-sm text-gray-600">Nilai Tertinggi</p>
                        <p class="text-2xl font-bold text-black">{{ $bestScore }}</p>
                    </div>
                </div>

                <!-- Progress Chart (Simple Table) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-black">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
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
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 text-black">{{ $result->created_at->format('d M Y') }}</td>
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
                                    <td class="px-6 py-4 text-black">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Belum ada hasil quiz untuk {{ $data['anak']->name }}</p>
            @endif
        </div>
    @endforeach
@else
    <div class="rounded-lg shadow border border-gray-200 p-12 text-center style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-gray-500">Belum ada data anak yang terdaftar</p>
    </div>
@endif
@endsection

