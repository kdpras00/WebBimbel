@extends('layouts.app')

@section('title', $quiz->judul)

@section('content')
<!-- Header Card -->
<div class="mb-6 bg-blue-400 rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $quiz->judul }}</h1>
            @if($quiz->deskripsi)
                <p class="text-blue-100">{{ $quiz->deskripsi }}</p>
            @endif
            <div class="mt-4 flex items-center gap-4 text-sm text-blue-100">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5 8.85V13a2 2 0 002 2h6a2 2 0 002-2V8.85l2.394-1.93a1 1 0 000-1.84l-7-3z"></path>
                    </svg>
                    {{ $quiz->mapel->kelas->nama }}
                </span>
                <span>•</span>
                <span>{{ $quiz->mapel->nama }}</span>
                @if($quiz->durasi)
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $quiz->durasi }} menit
                    </span>
                @endif
            </div>
        </div>
        <div class="text-right">
            <div class="text-2xl font-bold">{{ $quiz->questions->count() }}</div>
            <div class="text-sm text-blue-100">Soal</div>
        </div>
    </div>
</div>

<!-- Timer & Progress Bar -->
<div class="mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-white">Progress</span>
            <span class="text-sm font-medium text-white" id="progressText">0/{{ $quiz->questions->count() }}</span>
        </div>
        <div class="flex items-center gap-3">
            @if($quiz->durasi)
                <div id="timerContainer" class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-lg font-bold text-blue-700" id="timer">00:00</span>
                </div>
            @endif
            <div id="warningBadge" class="flex items-center gap-2 px-4 py-2 bg-yellow-100 rounded-lg border-2 border-yellow-300 transition-colors shadow-sm">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.721-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.492-1.646-1.742-2.98l5.58-9.92zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-2a1 1 0 01-1-1V7a1 1 0 112 0v3a1 1 0 01-1 1z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-semibold text-yellow-800">
                    Peringatan:
                    <span id="warningCount">{{ $session->warning_count }}</span>/{{ $maxWarnings }}
                </span>
            </div>
        </div>
    </div>
    <div class="w-full bg-blue-600/30 rounded-full h-3 backdrop-blur-sm">
        <div class="bg-yellow-400 h-3 rounded-full transition-all duration-500 shadow-[0_0_10px_rgba(250,204,21,0.5)]" id="progressBar" style="width: 0%"></div>
    </div>
</div>

<!-- Quiz Form -->
<div class="bg-gray-100 border border-gray-200 rounded-xl shadow-lg overflow-hidden">
    <form id="quizForm" action="{{ route('siswa.quiz.submit', $quiz->id) }}" method="POST">
        @csrf
        <input type="hidden" name="start_time" value="{{ optional($session->started_at)->timestamp ?? now()->timestamp }}">
        <input type="hidden" name="quiz_session_id" value="{{ $session->id }}">
        
        @foreach($quiz->questions as $index => $question)
            <div class="p-6 border-b border-gray-200 last:border-0 bg-gray-50/50">
                <!-- Question Header -->
                <div class="mb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-400 text-white font-bold text-lg shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800100 leading-tight">
                                {{ $question->pertanyaan }}
                            </h3>
                        </div>

                    </div>
                </div>

                <!-- Answer Options -->
                @if($question->tipe == 'pilihan_ganda')
                    <div class="space-y-3">
                        @foreach(is_array($question->pilihan) ? $question->pilihan : [] as $key => $pilihan)
                            <label for="question_{{ $question->id }}_{{ $key }}" 
                                   class="flex items-center p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-100 transition-all duration-200 group has-[:checked]:border-blue-400 has-[:checked]:bg-blue-100 shadow-sm hover:shadow-md">
                                <input type="radio" 
                                       id="question_{{ $question->id }}_{{ $key }}" 
                                       name="jawaban[{{ $question->id }}]" 
                                       value="{{ $key }}"
                                       class="w-5 h-5 text-blue-400 bg-white border-gray-300 focus:ring-blue-400 focus:ring-2 question-radio"
                                       data-question="{{ $question->id }}">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-blue-400 font-semibold text-sm group-hover:bg-blue-400 group-hover:text-white transition-colors has-[:checked]:bg-blue-400 has-[:checked]:text-white">
                                            {{ $key }}
                                        </span>
                                        <span class="text-gray-800200 font-medium">{{ $pilihan }}</span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-blue-400 opacity-0 group-hover:opacity-100 has-[:checked]:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div>
                        <textarea name="jawaban[{{ $question->id }}]" 
                                  rows="6"
                                  class="block w-full p-4 text-sm text-black bg-white rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all shadow-sm"
                                  placeholder="Tulis jawaban Anda di sini..."></textarea>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Submit Section -->
        <div class="p-6 bg-gray-100 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <a href="{{ route('siswa.quiz.index') }}" 
                   class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-blue-100 hover:border-blue-400 hover:text-blue-400 transition-all shadow-sm hover:shadow-md">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </span>
                </a>
                <button type="submit" 
                        class="px-8 py-3 text-sm font-semibold text-white bg-blue-400 rounded-lg hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-200 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 border-2 border-gray-300">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit Quiz
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Petunjuk & Doa (Professional Exam Style) -->
<div id="instructionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
    <div class="absolute inset-0 bg-slate-900/60"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-4 overflow-hidden animate-scale-in flex flex-col max-h-[90vh]">
        <!-- Top Decoration -->
        <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500 flex-shrink-0"></div>
        
        <div class="flex flex-col md:flex-row h-full overflow-hidden">
            <!-- Left Side: Exam Details -->
            <div class="w-full md:w-1/3 bg-slate-50 p-8 border-r border-slate-200 flex flex-col justify-center relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>

                <div class="relative z-10 text-center">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg mx-auto mb-6 flex items-center justify-center text-blue-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Konfirmasi Ujian</h2>
                    <p class="text-slate-500 text-sm mb-8">Harap cek detail ujian sebelum memulai</p>

                    <div class="space-y-4">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Durasi Pengerjaan</div>
                            <div class="font-bold text-slate-800 text-xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $quiz->durasi ?? '∞' }} Menit
                            </div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Jumlah Soal</div>
                            <div class="font-bold text-slate-800 text-xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                {{ $quiz->questions->count() }} Soal
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 shadow-sm">
                            <div class="text-xs text-yellow-600 uppercase tracking-wider mb-1">Target KKM</div>
                            <div class="font-bold text-yellow-700 text-xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $quiz->mapel->kkm }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Instructions -->
            <div class="w-full md:w-2/3 p-8 flex flex-col overflow-y-auto">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Tata Tertib & Petunjuk
                    </h3>
                    <div class="space-y-4">
                        <div class="flex gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white text-blue-600 border border-blue-100 flex items-center justify-center font-bold shadow-sm">1</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Waktu Pengerjaan</h4>
                                <p class="text-sm text-slate-500 mt-1">Waktu akan otomatis berjalan mundur saat Anda menekan tombol "Mulai Kerjakan". Ujian akan tertutup otomatis jika waktu habis.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white text-blue-600 border border-blue-100 flex items-center justify-center font-bold shadow-sm">2</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Integritas Ujian</h4>
                                <p class="text-sm text-slate-500 mt-1">Dilarang membuka tab lain, aplikasi lain, atau melakukan kecurangan. Sistem memantau aktivitas layar Anda.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white text-blue-600 border border-blue-100 flex items-center justify-center font-bold shadow-sm">3</div>
                            <div>
                                <h4 class="font-semibold text-slate-800 text-sm">Submit Jawaban</h4>
                                <p class="text-sm text-slate-500 mt-1">Periksa kembali seluruh jawaban sebelum melakukan Submit. Jawaban yang sudah disubmit tidak dapat diubah kembali.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-6 border-t border-slate-100 flex gap-4">
                    <a href="{{ route('siswa.quiz.index') }}" class="px-6 py-3 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                        Batalkan
                    </a>
                    <button id="startQuizBtn" class="flex-1 px-8 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                        <span>Saya Mengerti & Mulai Kerjakan</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes bounceSubtle {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-scale-in {
    animation: scaleIn 0.3s ease-out;
}

.animate-bounce-subtle {
    animation: bounceSubtle 2s ease-in-out infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalQuestions = {{ $quiz->questions->count() }};
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const instructionModal = document.getElementById('instructionModal');
    const startQuizBtn = document.getElementById('startQuizBtn');
    const quizForm = document.getElementById('quizForm');
    const timerContainer = document.getElementById('timerContainer');
    const timerElement = document.getElementById('timer');
    const warningBadge = document.getElementById('warningBadge');
    const warningCountElement = document.getElementById('warningCount');
    const warningIcon = warningBadge ? warningBadge.querySelector('svg') : null;
    const warningText = warningBadge ? warningBadge.querySelector('span.text-sm') : null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const endpoints = {
        pause: @json(route('siswa.quiz.session.pause', $quiz->id)),
        resume: @json(route('siswa.quiz.session.resume', $quiz->id)),
        warning: @json(route('siswa.quiz.session.warning', $quiz->id)),
    };
    const hasTimer = {{ $quiz->durasi ? 'true' : 'false' }};
    let initialTimeRemaining = {{ $remainingSeconds !== null ? $remainingSeconds : 'null' }};
    let timerInterval = null;
    let quizStarted = false;
    let quizPaused = false;
    let quizSubmitting = false;
    let blurStartTime = null;
    let blurTimeout = null;
    let warningCount = {{ $session->warning_count }};
    const maxWarnings = {{ $maxWarnings }};
    const quizSessionId = {{ $session->id }};
    // Timestamp-based timer untuk tetap berjalan saat tab tidak aktif
    let timerStartTime = null; // Timestamp saat timer mulai (dalam detik)
    let timerInitialSeconds = null; // Waktu tersisa saat timer mulai (dalam detik)

    if (!quizForm) {
        return;
    }

    if (instructionModal) {
        instructionModal.classList.remove('hidden');
    }

    quizForm.style.pointerEvents = 'none';
    quizForm.style.opacity = '0.5';

    function callLaravel(url, payload = {}) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(Object.assign({ quiz_session_id: quizSessionId }, payload)),
        }).then(async (response) => {
            const data = await response.json().catch(() => ({}));
            return { ok: response.ok, status: response.status, data };
        }).catch((error) => {
            console.error('Quiz session request failed', error);
            return { ok: false, status: 500, data: {} };
        });
    }

    function updateWarningUI() {
        if (!warningBadge || !warningCountElement) {
            return;
        }

        warningCountElement.textContent = warningCount;

        const baseBadgeClasses = ['bg-yellow-100', 'border-yellow-300', 'dark:bg-yellow-900/30', 'dark:border-yellow-700'];
        const dangerBadgeClasses = ['bg-red-100', 'border-red-300', 'dark:bg-red-900/30', 'dark:border-red-700'];
        const baseTextClasses = ['text-yellow-800', 'dark:text-yellow-200'];
        const dangerTextClasses = ['text-red-800', 'dark:text-red-200'];
        const baseIconClasses = ['text-yellow-600', 'dark:text-yellow-400'];
        const dangerIconClasses = ['text-red-600', 'dark:text-red-400'];

        warningBadge.classList.remove(...baseBadgeClasses, ...dangerBadgeClasses);
        warningBadge.classList.add(...(warningCount >= maxWarnings ? dangerBadgeClasses : baseBadgeClasses));

        if (warningText) {
            warningText.classList.remove(...baseTextClasses, ...dangerTextClasses);
            warningText.classList.add(...(warningCount >= maxWarnings ? dangerTextClasses : baseTextClasses));
        }

        if (warningIcon) {
            warningIcon.classList.remove(...baseIconClasses, ...dangerIconClasses);
            warningIcon.classList.add(...(warningCount >= maxWarnings ? dangerIconClasses : baseIconClasses));
        }
    }

    function setTimerAppearance(state) {
        if (!timerContainer || !timerElement) {
            return;
        }

        const stateStyles = {
            base: {
                badge: ['bg-gray-100', 'border-indigo-300', 'dark:bg-indigo-900/30', 'dark:border-indigo-700'],
                text: ['text-indigo-700', 'dark:text-indigo-300'],
            },
            warning: {
                badge: ['bg-yellow-100', 'border-yellow-300', 'dark:bg-yellow-900/30', 'dark:border-yellow-700'],
                text: ['text-yellow-700', 'dark:text-yellow-300'],
            },
            danger: {
                badge: ['bg-red-100', 'border-red-300', 'dark:bg-red-900/30', 'dark:border-red-700'],
                text: ['text-red-700', 'dark:text-red-300'],
            },
        };

        const allBadgeClasses = [
            ...stateStyles.base.badge,
            ...stateStyles.warning.badge,
            ...stateStyles.danger.badge,
        ];

        const allTextClasses = [
            ...stateStyles.base.text,
            ...stateStyles.warning.text,
            ...stateStyles.danger.text,
        ];

        timerContainer.classList.remove(...allBadgeClasses);
        timerElement.classList.remove(...allTextClasses);

        timerContainer.classList.add(...stateStyles[state].badge);
        timerElement.classList.add(...stateStyles[state].text);
    }

    function calculateTimeRemaining() {
        if (!hasTimer || timerInitialSeconds === null || timerStartTime === null) {
            return initialTimeRemaining;
        }

        // Timer tetap berjalan meskipun tab tidak aktif (tidak bergantung pada quizPaused)
        const now = Math.floor(Date.now() / 1000);
        const elapsed = now - timerStartTime;
        const remaining = Math.max(0, timerInitialSeconds - elapsed);
        
        return remaining;
    }

    function updateTimerDisplay() {
        if (!hasTimer || !timerElement) {
            return;
        }

        const timeRemaining = calculateTimeRemaining();
        
        if (timeRemaining === null) {
            return;
        }

        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        timerElement.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

        if (timeRemaining <= 60) {
            setTimerAppearance('danger');
        } else if (timeRemaining <= 300) {
            setTimerAppearance('warning');
        } else {
            setTimerAppearance('base');
        }
    }

    function startTimerInterval() {
        if (!hasTimer || initialTimeRemaining === null) {
            return;
        }

        // Inisialisasi timer berbasis timestamp
        if (timerStartTime === null) {
            timerStartTime = Math.floor(Date.now() / 1000);
            timerInitialSeconds = initialTimeRemaining;
        }

        clearInterval(timerInterval);
        updateTimerDisplay();

        // Timer interval hanya untuk update display, perhitungan waktu berdasarkan timestamp
        timerInterval = setInterval(function() {
            if (!quizStarted || quizSubmitting) {
                return;
            }

            const timeRemaining = calculateTimeRemaining();

            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                submitQuiz();
                return;
            }

            updateTimerDisplay();
        }, 100); // Update lebih sering untuk akurasi yang lebih baik
    }

    function pauseQuiz() {
        // Timer tidak lagi dipause saat alt-tab, hanya untuk keperluan server tracking
        if (!quizStarted || quizPaused || quizSubmitting) {
            return;
        }

        quizPaused = true;

        callLaravel(endpoints.pause).then(({ ok, data }) => {
            if (ok && typeof data.remaining_seconds === 'number') {
                // Update timer dengan waktu dari server
                timerStartTime = Math.floor(Date.now() / 1000);
                timerInitialSeconds = data.remaining_seconds;
                initialTimeRemaining = data.remaining_seconds;
                updateTimerDisplay();
            }

            if (!ok && data && data.status === 'submitted') {
                submitQuiz();
            }
        });
    }

    function resumeQuiz() {
        if (!quizStarted || quizSubmitting) {
            return;
        }

        callLaravel(endpoints.resume).then(({ ok, status, data }) => {
            if (!ok) {
                if (status === 410 || (data && data.status === 'time_up')) {
                    submitQuiz();
                }
                return;
            }

            if (data && typeof data.remaining_seconds === 'number') {
                // Update timer dengan waktu dari server
                timerStartTime = Math.floor(Date.now() / 1000);
                timerInitialSeconds = data.remaining_seconds;
                initialTimeRemaining = data.remaining_seconds;
            }

            quizPaused = false;
            startTimerInterval();
        });
    }

    function logWarning() {
        if (!quizStarted || quizSubmitting) {
            return;
        }

        warningCount = Math.min(warningCount + 1, maxWarnings);
        updateWarningUI();

        if (warningCount >= maxWarnings) {
            submitQuiz();
        }

        callLaravel(endpoints.warning).then(({ ok, data }) => {
            if (ok && data) {
                if (typeof data.warning_count === 'number') {
                    warningCount = data.warning_count;
                }

                updateWarningUI();

                if (data.auto_submit) {
                    submitQuiz();
                }
            } else if (data && data.auto_submit) {
                submitQuiz();
            }
        });
    }

    function submitQuiz() {
        if (quizSubmitting) {
            return;
        }

        quizSubmitting = true;
        clearInterval(timerInterval);
        clearTimeout(blurTimeout);
        quizForm.requestSubmit();
    }

    function startQuiz() {
        if (quizStarted) {
            return;
        }

        if (instructionModal) {
            instructionModal.classList.add('hidden');
        }

        quizForm.style.pointerEvents = 'auto';
        quizForm.style.opacity = '1';
        quizStarted = true;
        quizPaused = false;

        // Inisialisasi timer berbasis timestamp
        if (hasTimer && initialTimeRemaining !== null) {
            timerStartTime = Math.floor(Date.now() / 1000);
            timerInitialSeconds = initialTimeRemaining;
        }

        updateWarningUI();
        updateTimerDisplay();
        startTimerInterval();

        const startTimeField = document.querySelector('input[name="start_time"]');
        if (startTimeField) {
            startTimeField.value = Math.floor(Date.now() / 1000);
        }
    }

    function updateProgress() {
        const answered = document.querySelectorAll('.question-radio:checked').length;
        const progress = totalQuestions > 0 ? (answered / totalQuestions) * 100 : 0;
        progressBar.style.width = progress + '%';
        progressText.textContent = answered + '/' + totalQuestions;
    }

    if (startQuizBtn) {
        startQuizBtn.addEventListener('click', startQuiz);
    }

    document.querySelectorAll('.question-radio').forEach(function(radio) {
        radio.addEventListener('change', updateProgress);
    });

    updateProgress();
    updateWarningUI();
    updateTimerDisplay();

    quizForm.addEventListener('submit', function(e) {
        if (quizSubmitting) {
            return;
        }

        if (!quizStarted) {
            e.preventDefault();
            return;
        }

        const answered = document.querySelectorAll('.question-radio:checked').length;
        let message = 'Apakah Anda yakin ingin mengumpulkan jawaban?';

        if (answered < totalQuestions) {
            message = 'Anda belum menjawab semua soal. ' + message;
        }

        // Use SweetAlert if available, otherwise fallback to confirm
        if (typeof Swal !== 'undefined') {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Submit',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Submit!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    quizSubmitting = true;
                    clearInterval(timerInterval);
                    quizForm.submit();
                }
            });
            return;
        } else {
            const confirmSubmit = confirm(message);
            if (!confirmSubmit) {
                e.preventDefault();
                return;
            }
        }

        quizSubmitting = true;
        clearInterval(timerInterval);
    });

    window.addEventListener('blur', function() {
        if (!quizStarted || quizSubmitting) {
            return;
        }

        blurStartTime = Date.now();
        // Timer tetap berjalan, hanya log warning
        logWarning();

        clearTimeout(blurTimeout);
        blurTimeout = setTimeout(function() {
            const diff = Date.now() - (blurStartTime || Date.now());
            if (diff >= 10000) {
                // Jika tab tidak aktif lebih dari 10 detik, submit otomatis
                submitQuiz();
            }
        }, 10000);
    });

    window.addEventListener('focus', function() {
        if (!quizStarted || quizSubmitting) {
            return;
        }

        clearTimeout(blurTimeout);
        blurStartTime = null;
        
        // Sync waktu dengan server saat kembali ke tab
        if (hasTimer && initialTimeRemaining !== null) {
            callLaravel(endpoints.resume).then(({ ok, data }) => {
                if (ok && data && typeof data.remaining_seconds === 'number') {
                    timerStartTime = Math.floor(Date.now() / 1000);
                    timerInitialSeconds = data.remaining_seconds;
                    initialTimeRemaining = data.remaining_seconds;
                    updateTimerDisplay();
                }
            });
        }
    });

    document.addEventListener('visibilitychange', function() {
        if (!quizStarted || quizSubmitting) {
            return;
        }

        if (document.hidden) {
            // Timer tetap berjalan, hanya log warning
            logWarning();
        } else {
            // Sync waktu dengan server saat tab kembali aktif
            if (hasTimer && initialTimeRemaining !== null) {
                callLaravel(endpoints.resume).then(({ ok, data }) => {
                    if (ok && data && typeof data.remaining_seconds === 'number') {
                        timerStartTime = Math.floor(Date.now() / 1000);
                        timerInitialSeconds = data.remaining_seconds;
                        initialTimeRemaining = data.remaining_seconds;
                        updateTimerDisplay();
                    }
                });
            }
        }
    });

    window.addEventListener('beforeunload', function(e) {
        const timeRemaining = calculateTimeRemaining();
        if (quizStarted && !quizSubmitting && (timeRemaining === null || timeRemaining > 0)) {
            e.preventDefault();
            e.returnValue = 'Apakah Anda yakin ingin meninggalkan halaman? Progress quiz Anda akan hilang.';
            return e.returnValue;
        }
    });
});
</script>
@endsection

