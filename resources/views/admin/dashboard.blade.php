@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8 animate-fade-in-down">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Dashboard Overview</h1>
            <p class="mt-2 text-blue-100">Ringkasan statistik dan performa bimbel hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 shadow-sm">
                {{ now()->format('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total User Card -->
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full opacity-50  transition-transform duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Pengguna</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_users'] }}</h3>
                    <div class="mt-2 flex items-center text-xs text-green-600 bg-green-50 px-2 py-1 rounded-md w-fit">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>Aktif</span>
                    </div>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl text-blue-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Kelas Card -->
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 rounded-full opacity-50  transition-transform duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Kelas</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_kelas'] }}</h3>
                    <div class="mt-2 flex items-center text-xs text-slate-500 bg-slate-50 px-2 py-1 rounded-md w-fit">
                        <span>Kelas Tersedia</span>
                    </div>
                </div>
                <div class="p-4 bg-green-50 rounded-xl text-green-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Mapel Card -->
        <div class="relative overflow-hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-50 rounded-full opacity-50  transition-transform duration-300"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Mata Pelajaran</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_mapel'] }}</h3>
                    <div class="mt-2 flex items-center text-xs text-slate-500 bg-slate-50 px-2 py-1 rounded-md w-fit">
                        <span>Kurikulum Aktif</span>
                    </div>
                </div>
                <div class="p-4 bg-orange-50 rounded-xl text-orange-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed User Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pengajar -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all duration-300">
            <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center text-purple-600 mb-4  transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-slate-800">Pengajar</h4>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['total_pengajar'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Guru & Mentor</p>
        </div>

        <!-- Siswa -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all duration-300">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mb-4  transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-slate-800">Siswa</h4>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_siswa'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Peserta Didik</p>
        </div>

        <!-- Wali -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all duration-300">
            <div class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 mb-4  transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-slate-800">Wali Murid</h4>
            <p class="text-3xl font-bold text-teal-600 mt-2">{{ $stats['total_wali'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Orang Tua/Wali</p>
        </div>
    </div>
</div>
@endsection

