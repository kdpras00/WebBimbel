@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Siswa</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Poin</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPoin }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Quiz</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalQuiz }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg dark:bg-green-900">
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($averageScore, 1) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Results -->
<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hasil Quiz Terakhir</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Quiz</th>
                    <th scope="col" class="px-6 py-3">Nilai</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentResults as $result)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $result->quiz->judul }}</td>
                        <td class="px-6 py-4">{{ $result->nilai }}</td>
                        <td class="px-6 py-4">{{ $result->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada hasil quiz</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

