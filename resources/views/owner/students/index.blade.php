@extends('layouts.app')

@section('title', 'Progress Siswa')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Laporan Perkembangan Siswa</h1>
            <p class="mt-2 text-blue-100">Pantau kemajuan belajar setiap siswa</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <form action="{{ route('owner.students.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                    placeholder="Cari nama siswa atau email...">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Siswa</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Quiz</th>
                        <th scope="col" class="px-6 py-3 text-center">Rata-rata Nilai</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">
                                <div class="flex flex-col">
                                    <span>{{ $student->name }}</span>
                                    <span class="text-xs text-slate-400">{{ $student->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">{{ $student->total_quiz }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $student->avg_score >= 80 ? 'bg-green-100 text-green-700' : 
                                       ($student->avg_score >= 70 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ number_format($student->avg_score, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('owner.students.show', $student->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                    Lihat Progress
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                {{ request('search') ? 'Tidak ada siswa yang ditemukan' : 'Belum ada data siswa' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
