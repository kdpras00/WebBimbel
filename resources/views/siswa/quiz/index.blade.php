@extends('layouts.app')

@section('title', 'Quiz & Latihan Soal')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Quiz & Latihan Soal</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Pilih quiz yang ingin dikerjakan</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($quizzes as $quiz)
        <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                        {{ $quiz->mapel->kelas->nama }}
                    </span>
                    @if($quiz->is_published)
                        <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Published
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                            Draft
                        </span>
                    @endif
                </div>
                
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->judul }}</h3>
                @if($quiz->deskripsi)
                    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($quiz->deskripsi, 100) }}</p>
                @endif
                
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <span>{{ $quiz->mapel->nama }}</span>
                    @if($quiz->durasi)
                        <span>⏱️ {{ $quiz->durasi }} menit</span>
                    @endif
                </div>
                
                @if($quiz->is_published)
                    <a href="{{ route('siswa.quiz.show', $quiz->id) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Mulai Quiz
                    </a>
                @else
                    <button disabled 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-gray-400 rounded-lg cursor-not-allowed">
                        Belum Tersedia
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 dark:text-gray-400">Belum ada quiz yang tersedia</p>
        </div>
    @endforelse
</div>
@endsection

