@extends('layouts.app')

@section('title', 'Performa Pengajar')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Performa Pengajar</h1>
            <p class="mt-2 text-blue-100">Analisis kinerja dan produktivitas pengajar</p>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Daftar Pengajar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Quiz</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Pengerjaan Siswa</th>
                        <th scope="col" class="px-6 py-3 text-center">Rata-rata Nilai Siswa</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">
                                <div class="flex flex-col">
                                    <span>{{ $teacher->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $teacher->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">{{ $teacher->total_quiz }}</td>
                            <td class="px-6 py-4 text-center">{{ $teacher->total_results }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $teacher->avg_score >= 80 ? 'bg-green-100 text-green-700' : 
                                       ($teacher->avg_score >= 70 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ number_format($teacher->avg_score, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('owner.teachers.show', $teacher->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data pengajar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
