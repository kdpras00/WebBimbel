@extends('layouts.app')

@section('title', 'Detail Progress Siswa')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-blue-100 mb-2">
                <a href="{{ route('owner.students.index') }}" class="hover:text-white transition-colors">Progress Siswa</a>
                <span>/</span>
                <span>Detail</span>
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $student->name }}</h1>
            <p class="mt-2 text-blue-100">{{ $student->email }}</p>
        </div>
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
                    @forelse($results as $result)
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
