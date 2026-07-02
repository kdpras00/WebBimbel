@extends('layouts.app')

@section('title', 'Hasil Quiz')

@section('content')
@php
    $reverseOptionMapping = $reverseOptionMapping ?? [];
    $kkm = $result->quiz->mapel->kkm ?? 70;
    $lulus = $result->nilai >= $kkm;
    $salah = $result->total_soal - $result->jawaban_benar;
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Score Card -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <!-- Header Section with Gradient -->
        <div class="p-8 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);">
            <!-- Decorative Circles (Simplified opacity) -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-white opacity-10"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold mb-2 text-white">{{ $result->quiz->judul }}</h1>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-blue-50 text-sm font-medium">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ gmdate('i:s', $result->waktu_pengerjaan) }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $result->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="text-6xl font-black tracking-tight mb-2 text-white">{{ round($result->nilai) }}</div>
                    <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $lulus ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }} shadow-sm">
                        {{ $lulus ? 'LULUS' : 'TIDAK LULUS' }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-slate-100 bg-white border-b border-slate-100">
            <div class="p-6 text-center">
                <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Benar</div>
                <div class="text-2xl font-bold text-green-600">{{ $result->jawaban_benar }}</div>
                <div class="text-xs text-slate-400">Soal</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Salah</div>
                <div class="text-2xl font-bold text-red-600">{{ $salah }}</div>
                <div class="text-xs text-slate-400">Soal</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Total Soal</div>
                <div class="text-2xl font-bold text-slate-700">{{ $result->total_soal }}</div>
                <div class="text-xs text-slate-400">Butir</div>
            </div>
            <div class="p-6 text-center">
                <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">KKM</div>
                <div class="text-2xl font-bold text-slate-700">{{ $kkm }}</div>
                <div class="text-xs text-slate-400">Minimal</div>
            </div>
        </div>
    </div>

    <!-- Review Section Header -->
    <div class="flex items-center justify-between px-2">
        <h2 class="text-lg font-bold text-white">Pembahasan Soal</h2>
        <div class="flex gap-2">
            <div class="flex items-center gap-1 text-xs text-white/80">
                <span class="w-3 h-3 rounded-full bg-green-100 border border-green-200"></span> Benar
            </div>
            <div class="flex items-center gap-1 text-xs text-white/80">
                <span class="w-3 h-3 rounded-full bg-red-100 border border-red-200"></span> Salah
            </div>
            <div class="flex items-center gap-1 text-xs text-white/80">
                <span class="w-3 h-3 rounded-full bg-yellow-100 border border-yellow-200"></span> Tidak Dijawab
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @foreach($result->quiz->questions as $index => $question)
            @php
                $userAnswerOriginal = $result->jawaban[$question->id] ?? null;
                $userAnswer = $userAnswerOriginal;
                if (isset($reverseOptionMapping) && isset($reverseOptionMapping[$question->id]) && isset($reverseOptionMapping[$question->id][$userAnswerOriginal])) {
                    $userAnswer = $reverseOptionMapping[$question->id][$userAnswerOriginal];
                }
                $originalJawabanBenar = $question->original_jawaban_benar ?? $question->jawaban_benar;
                $isUnanswered = ($userAnswerOriginal === null || $userAnswerOriginal === '');
                $isCorrect = !$isUnanswered && ($userAnswerOriginal == $originalJawabanBenar);
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm {{ $isCorrect ? 'bg-green-100 text-green-600' : ($isUnanswered ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <h3 class="text-slate-800 font-medium text-lg">{{ $question->pertanyaan }}</h3>
                                @if($isUnanswered)
                                    <span class="flex-shrink-0 px-2.5 py-1 rounded-lg text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        TIDAK DIJAWAB
                                    </span>
                                @endif
                            </div>

                            @if($question->tipe == 'pilihan_ganda')
                                <div class="space-y-2">
                                    @foreach($question->pilihan as $key => $pilihan)
                                        @php
                                            $isKeyCorrect = $key == $question->jawaban_benar;
                                            $isKeyUserAnswer = $key == $userAnswer;
                                            
                                            $baseClass = "relative flex items-center p-3 rounded-xl border transition-all";
                                            $colorClass = "bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100";
                                            
                                            if ($isKeyCorrect) {
                                                $colorClass = "bg-green-50 border-green-200 text-green-800 ring-1 ring-green-200";
                                            } elseif ($isKeyUserAnswer && !$isCorrect) {
                                                $colorClass = "bg-red-50 border-red-200 text-red-800 ring-1 ring-red-200";
                                            }
                                        @endphp
                                        
                                        <div class="{{ $baseClass }} {{ $colorClass }}">
                                            <span class="w-8 flex-shrink-0 font-bold opacity-70">{{ $key }}.</span>
                                            <span class="flex-grow font-medium">{{ $pilihan }}</span>
                                            
                                            @if($isKeyCorrect)
                                                <svg class="w-5 h-5 text-green-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif($isKeyUserAnswer)
                                                <svg class="w-5 h-5 text-red-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-2 font-bold">Jawaban Anda</p>
                                    <p class="text-slate-800 {{ $isUnanswered ? 'italic text-slate-400' : '' }}">{{ $userAnswerOriginal ?? 'Tidak ada jawaban' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center pt-6 pb-12">
        <a href="{{ route('siswa.quiz.index') }}" class="inline-flex items-center px-8 py-3 text-base font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Quiz
        </a>
    </div>
</div>
@endsection

