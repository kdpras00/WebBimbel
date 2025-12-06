@extends('layouts.app')

@section('title', 'Detail Quiz')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('owner.teachers.show', $quiz->pengajar_id) }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">Detail Quiz</h1>
            </div>
            <p class="text-white ml-7">Statistik detail pengerjaan quiz per siswa</p>
        </div>
    </div>

    <!-- Quiz Info Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-sm font-medium text-slate-500">Judul Quiz</p>
                <p class="text-lg font-bold text-slate-800">{{ $quiz->judul }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Mata Pelajaran</p>
                <p class="text-lg font-bold text-slate-800">{{ $quiz->mapel->nama }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pengajar</p>
                <p class="text-lg font-bold text-slate-800">{{ $quiz->pengajar->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Status</p>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $quiz->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700' }}">
                    {{ $quiz->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Submission Status -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Status Pengerjaan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-blue-50 rounded-xl text-center">
                    <p class="text-sm font-medium text-blue-600 mb-1">Sudah Mengerjakan</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $stats['submitted'] }}</p>
                    <p class="text-xs text-blue-500 mt-1">Siswa</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl text-center">
                    <p class="text-sm font-medium text-slate-600 mb-1">Belum Mengerjakan</p>
                    <p class="text-2xl font-bold text-slate-700">{{ $stats['not_submitted'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">Siswa</p>
                </div>
            </div>
        </div>

        <!-- Grade Status -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Status Kelulusan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-green-50 rounded-xl text-center">
                    <p class="text-sm font-medium text-green-600 mb-1">Lulus (>=70)</p>
                    <p class="text-2xl font-bold text-green-700">{{ $stats['passed'] }}</p>
                    <p class="text-xs text-green-500 mt-1">Siswa</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl text-center">
                    <p class="text-sm font-medium text-red-600 mb-1">Tidak Lulus</p>
                    <p class="text-2xl font-bold text-red-700">{{ $stats['failed'] }}</p>
                    <p class="text-xs text-red-500 mt-1">Siswa</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Daftar Siswa</h3>
            <span class="text-sm text-slate-500">Total: {{ $student_data->count() }} Siswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Siswa</th>
                        <th scope="col" class="px-6 py-3 text-center">Status Pengerjaan</th>
                        <th scope="col" class="px-6 py-3 text-center">Nilai</th>
                        <th scope="col" class="px-6 py-3 text-center">Status Kelulusan</th>
                        <th scope="col" class="px-6 py-3 text-center">Waktu Submit</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($student_data as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $student->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $student->status == 'Sudah Dikerjakan' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-800">
                                {{ $student->score !== null ? number_format($student->score, 1) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($student->pass_status)
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        {{ $student->pass_status == 'Lulus' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $student->pass_status }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs">
                                {{ $student->submitted_at ? $student->submitted_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('owner.students.show', $student->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Lihat Progress</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">Tidak ada data siswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
