@extends('layouts.app')

@section('title', 'Materi Pembelajaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Materi Pembelajaran</h1>
    <p class="mt-2 text-gray-100">Akses semua materi pembelajaran yang tersedia</p>
</div>

<!-- Search Bar -->
<div class="mb-6">
    <form method="GET" action="{{ route('siswa.materi.index') }}" class="flex gap-2">
        <div class="flex-1 relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari materi berdasarkan judul, deskripsi, mapel, atau kelas..." 
                   class="w-full px-4 py-3 pl-10 text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <button type="submit" 
                class="px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('siswa.materi.index') }}" 
               class="px-6 py-3 text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($materi as $m)
        <div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                        {{ $m->mapel->kelas->nama }}
                    </span>
                    <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">
                        {{ ucfirst($m->tipe) }}
                    </span>
                </div>
                
                <h3 class="mb-2 text-xl font-bold text-black">{{ $m->judul }}</h3>
                <p class="mb-4 text-sm text-gray-700">{{ Str::limit($m->deskripsi, 100) }}</p>
                
                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                    <span>{{ $m->mapel->nama }}</span>
                    <span>{{ $m->pengajar->name }}</span>
                </div>
                
                <a href="{{ route('siswa.materi.show', $m->id) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    Baca Materi
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            @if(request('search'))
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="mt-4 text-lg font-medium text-gray-900">Tidak ada hasil ditemukan</p>
                <p class="mt-2 text-sm text-gray-600">Coba cari dengan kata kunci lain atau <a href="{{ route('siswa.materi.index') }}" class="text-blue-600 hover:text-blue-700">lihat semua materi</a></p>
            @else
                <p class="text-gray-100">Belum ada materi yang tersedia</p>
            @endif
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $materi->links('vendor.pagination.custom') }}
</div>
@endsection

