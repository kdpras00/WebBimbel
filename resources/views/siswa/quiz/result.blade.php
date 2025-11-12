@extends('layouts.app')

@section('title', 'Hasil Quiz')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Hasil Quiz</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $result->quiz->judul }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg dark:bg-blue-900">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nilai</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $result->nilai }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg dark:bg-green-900">
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jawaban Benar</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-900">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Waktu</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ gmdate('i:s', $result->waktu_pengerjaan) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Review Jawaban</h2>
    
    @foreach($result->quiz->questions as $index => $question)
        <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Soal {{ $index + 1 }}. {{ $question->pertanyaan }}
                </h3>
            </div>

            @php
                $userAnswer = $result->jawaban[$question->id] ?? null;
                $isCorrect = $userAnswer == $question->jawaban_benar;
            @endphp

            @if($question->tipe == 'pilihan_ganda')
                <div class="space-y-2">
                    @foreach($question->pilihan as $key => $pilihan)
                        <div class="flex items-center p-3 rounded-lg 
                            @if($key == $question->jawaban_benar) bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800
                            @elseif($key == $userAnswer && !$isCorrect) bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                            @else bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600
                            @endif">
                            <span class="font-medium mr-2">{{ $key }}.</span>
                            <span>{{ $pilihan }}</span>
                            @if($key == $question->jawaban_benar)
                                <span class="ml-auto text-green-600 dark:text-green-400">✓ Benar</span>
                            @elseif($key == $userAnswer && !$isCorrect)
                                <span class="ml-auto text-red-600 dark:text-red-400">✗ Jawaban Anda</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Jawaban Anda:</p>
                    <p class="text-gray-900 dark:text-white">{{ $userAnswer ?? 'Tidak dijawab' }}</p>
                </div>
            @endif
        </div>
    @endforeach

    <div class="flex justify-end gap-3">
        <a href="{{ route('siswa.quiz.index') }}" 
           class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Kembali ke Daftar Quiz
        </a>
    </div>
</div>
@endsection

