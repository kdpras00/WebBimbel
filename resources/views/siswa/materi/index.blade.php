@extends('layouts.app')

@section('title', 'Materi Pembelajaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Materi Pembelajaran</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Akses semua materi pembelajaran yang tersedia</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($materi as $m)
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                        {{ $m->mapel->kelas->nama }}
                    </span>
                    <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                        {{ ucfirst($m->tipe) }}
                    </span>
                </div>
                
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">{{ $m->judul }}</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($m->deskripsi, 100) }}</p>
                
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <span>{{ $m->mapel->nama }}</span>
                    <span>{{ $m->pengajar->name }}</span>
                </div>
                
                <a href="{{ route('siswa.materi.show', $m->id) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Baca Materi
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Belum ada materi yang tersedia</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $materi->links() }}
</div>
@endsection

