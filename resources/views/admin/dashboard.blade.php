@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Dashboard Admin</h1>
    <p class="mt-2 text-gray-100">Ringkasan sistem bimbel</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-700">Total User</p>
                <p class="text-2xl font-bold text-black">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-700">Total Kelas</p>
                <p class="text-2xl font-bold text-black">{{ $stats['total_kelas'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-700">Total Mapel</p>
                <p class="text-2xl font-bold text-black">{{ $stats['total_mapel'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- User Stats -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-700">Pengajar</p>
        <p class="text-2xl font-bold text-black">{{ $stats['total_pengajar'] }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-700">Siswa</p>
        <p class="text-2xl font-bold text-black">{{ $stats['total_siswa'] }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-700">Wali</p>
        <p class="text-2xl font-bold text-black">{{ $stats['total_wali'] }}</p>
    </div>
</div>
@endsection

