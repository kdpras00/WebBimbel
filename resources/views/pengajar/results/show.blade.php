@extends('layouts.app')

@section('title', 'Detail Hasil Quiz')

@section('content')
<div class="mb-6">
    <a href="{{ route('pengajar.results.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
        ← Kembali ke Daftar Hasil
    </a>
    <h1 class="text-3xl font-bold text-black">Detail Hasil Quiz</h1>
    <p class="mt-2 text-gray-600">{{ $result->quiz->judul }} - {{ $result->siswa->name }}</p>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-600">Nilai</p>
        <p class="text-2xl font-bold text-black">{{ $result->nilai }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-600">Jawaban Benar</p>
        <p class="text-2xl font-bold text-black">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-600">Waktu Pengerjaan</p>
        <p class="text-2xl font-bold text-black">{{ gmdate('i:s', $result->waktu_pengerjaan) }}</p>
    </div>
    <div class="p-6 rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-sm font-medium text-gray-600">Attempt</p>
        <p class="text-2xl font-bold text-black">{{ $result->attempt }}</p>
    </div>
</div>

<!-- Review Answers -->
<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);" mb-6>
    <h2 class="text-xl font-semibold mb-4 text-black">Review Jawaban</h2>
    
    @foreach($result->quiz->questions as $index => $question)
        <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-black">
                    Soal {{ $index + 1 }}. {{ $question->pertanyaan }}
                </h3>
                <span class="text-sm text-gray-500">Skor: {{ $question->skor }} poin</span>
            </div>

            @php
                $userAnswer = $result->jawaban[$question->id] ?? null;
                $isCorrect = $userAnswer == $question->jawaban_benar;
            @endphp

            @if($question->tipe == 'pilihan_ganda')
                <div class="space-y-2">
                    @foreach(is_array($question->pilihan) ? $question->pilihan : [] as $key => $pilihan)
                        <div class="flex items-center p-3 rounded-lg 
                            @if($key == $question->jawaban_benar) bg-green-50/20 border border-green-200
                            @elseif($key == $userAnswer && !$isCorrect) bg-red-50/20 border border-red-200
                            @else bg-gray-50 border border-gray-200
                            @endif">
                            <span class="font-medium mr-2">{{ $key }}.</span>
                            <span>{{ $pilihan }}</span>
                            @if($key == $question->jawaban_benar)
                                <span class="ml-auto text-green-600">✓ Benar</span>
                            @elseif($key == $userAnswer && !$isCorrect)
                                <span class="ml-auto text-red-600">✗ Jawaban Siswa</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Jawaban Siswa:</p>
                    <p class="text-black">{{ $userAnswer ?? 'Tidak dijawab' }}</p>
                    <p class="text-sm text-gray-600 mt-2">Jawaban Benar: {{ $question->jawaban_benar }}</p>
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Feedback Section -->
@if($result->feedback->count() > 0)
    <div class="rounded-lg shadow border border-gray-200 p-6 mb-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <h2 class="text-xl font-semibold mb-4 text-black">Feedback</h2>
        @foreach($result->feedback as $feedback)
            <div class="mb-4 p-4 bg-blue-50/20 rounded-lg">
                <p class="text-black">{{ $feedback->komentar }}</p>
                <p class="text-sm text-gray-500 mt-2">{{ $feedback->created_at->format('d M Y H:i') }}</p>
            </div>
        @endforeach
    </div>
@endif

<div class="flex gap-3 mt-6">
    <a href="{{ route('pengajar.feedback.create', $result->id) }}" 
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        Beri Feedback
    </a>
    <a href="{{ route('pengajar.results.index') }}" 
       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
        Kembali
    </a>
</div>
@endsection

