@extends('layouts.app')

@section('title', $quiz->judul)

@section('content')
<div class="max-w-7xl mx-auto">
    <form id="quizForm" action="{{ route('siswa.quiz.submit', $quiz->id) }}" method="POST">
        @csrf
        <input type="hidden" name="start_time" value="{{ optional($session->started_at)->timestamp ?? now()->timestamp }}">
        <input type="hidden" name="quiz_session_id" value="{{ $session->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Column: Questions (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Mobile Header -->
                <div class="lg:hidden bg-white rounded-xl shadow-sm border border-slate-200 p-4 sticky top-20 z-20">
                    <div class="flex items-center justify-between">
                        <h1 class="text-lg font-bold text-slate-800 truncate pr-4">{{ $quiz->judul }}</h1>
                        <div id="mobileTimer" class="font-mono text-xl font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">00:00</div>
                    </div>
                </div>

                @foreach($quiz->questions as $index => $question)
                    <div id="question-card-{{ $index }}" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden scroll-mt-32 group hover:border-blue-300 transition-colors duration-300">
                        <div class="p-6 md:p-8">
                            <!-- Question Header -->
                            <div class="flex gap-5">
                                <div class="flex-shrink-0">
                                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 text-slate-600 font-bold text-lg border border-slate-200 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex-grow space-y-4">
                                    <div class="prose prose-slate max-w-none">
                                        <p class="text-lg text-slate-800 font-medium leading-relaxed">
                                            {{ $question->pertanyaan }}
                                        </p>
                                    </div>

                                    <!-- Options -->
                                    @if($question->tipe == 'pilihan_ganda')
                                        <div class="space-y-3 mt-4">
                                            @foreach(is_array($question->pilihan) ? $question->pilihan : [] as $key => $pilihan)
                                                <label class="relative flex items-start p-4 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 hover:border-blue-300 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50 has-[:checked]:shadow-sm">
                                                    <div class="flex items-center h-6">
                                                        <input type="radio" 
                                                               name="jawaban[{{ $question->id }}]" 
                                                               value="{{ $key }}"
                                                               class="peer sr-only question-radio"
                                                               data-index="{{ $index }}"
                                                               data-question="{{ $question->id }}">
                                                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-all relative flex items-center justify-center">
                                                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 flex-1">
                                                        <div class="flex gap-2">
                                                            <span class="font-bold text-slate-500 w-6">{{ $key }}.</span>
                                                            <span class="text-slate-700 font-medium leading-relaxed">{{ $pilihan }}</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="mt-4">
                                            <textarea name="jawaban[{{ $question->id }}]" 
                                                      rows="5"
                                                      class="w-full p-4 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all resize-none text-slate-700 placeholder-slate-400 question-text leading-relaxed"
                                                      data-index="{{ $index }}"
                                                      placeholder="Ketik jawaban Anda di sini..."></textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-3 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Bobot Soal</span>
                            <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 text-xs font-bold shadow-sm">
                                {{ $question->skor }} Poin
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Column: Sticky Sidebar (4 cols) -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <!-- Timer & Status Card -->
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden ring-1 ring-slate-900/5">
                        <!-- Timer Header -->
                        <div class="bg-slate-900 p-6 text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-600 opacity-90"></div>
                            <div class="relative z-10">
                                <span class="text-xs font-bold text-blue-100 uppercase tracking-widest mb-1 block">Sisa Waktu</span>
                                <div id="timer" class="text-4xl font-black text-white font-mono tracking-tight tabular-nums">00:00</div>
                            </div>
                        </div>
                        
                        <!-- Warning Section -->
                        <div id="warningBadge" class="px-6 py-4 bg-yellow-50 border-b border-yellow-100 flex items-center justify-between transition-colors">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span class="text-sm font-bold text-yellow-800">Peringatan</span>
                            </div>
                            <span class="text-sm font-bold text-yellow-800 bg-yellow-200/50 px-2.5 py-0.5 rounded-full border border-yellow-200">
                                <span id="warningCount">{{ $session->warning_count }}</span>/{{ $maxWarnings }}
                            </span>
                        </div>

                        <!-- Navigation Grid -->
                        <div class="p-6">
                                <!-- <div class="flex items-center justify-between mb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Navigasi Soal</span>
                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded" id="progressText">0/{{ $quiz->questions->count() }}</span>
                                </div> -->
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($quiz->questions as $index => $question)
                                    <button type="button" 
                                            onclick="scrollToQuestion({{ $index }})"
                                            id="nav-btn-{{ $index }}"
                                            class="aspect-square rounded-lg flex items-center justify-center text-sm font-bold transition-all border border-slate-200 text-slate-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50">
                                        {{ $index + 1 }}
                                    </button>
                                @endforeach
                            </div>
                            
                            <div class="mt-4 flex gap-4 text-xs text-slate-400 justify-center">
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 rounded bg-white border border-slate-200"></div>
                                    <span>Belum</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 rounded bg-blue-600 border border-blue-600"></div>
                                    <span>Sudah</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-3">
                            <button type="submit" id="submitBtn" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 group">
                                <span>Selesai & Kumpulkan</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function scrollToQuestion(index) {
    const element = document.getElementById(`question-card-${index}`);
    if (element) {
        // Offset for sticky header
        const y = element.getBoundingClientRect().top + window.scrollY - 100;
        window.scrollTo({top: y, behavior: 'smooth'});
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const totalQuestions = {{ $quiz->questions->count() }};
    const progressText = document.getElementById('progressText');
    const mobileProgress = document.getElementById('mobileProgress');
    const quizForm = document.getElementById('quizForm');
    const timerElement = document.getElementById('timer');
    const mobileTimer = document.getElementById('mobileTimer');
    const warningBadge = document.getElementById('warningBadge');
    const warningCountElement = document.getElementById('warningCount');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    const endpoints = {
        pause: @json(route('siswa.quiz.session.pause', $quiz->id)),
        resume: @json(route('siswa.quiz.session.resume', $quiz->id)),
        warning: @json(route('siswa.quiz.session.warning', $quiz->id)),
    };
    
    const hasTimer = {{ $quiz->durasi ? 'true' : 'false' }};
    let initialTimeRemaining = {{ $remainingSeconds !== null ? $remainingSeconds : 'null' }};
    let timerInterval = null;
    let quizStarted = true;
    let quizPaused = false;
    let quizSubmitting = false;
    let blurStartTime = null;
    let blurTimeout = null;
    let warningCount = {{ $session->warning_count }};
    const maxWarnings = {{ $maxWarnings }};
    const quizSessionId = {{ $session->id }};
    
    let timerStartTime = Math.floor(Date.now() / 1000);
    let timerInitialSeconds = initialTimeRemaining;

    if (!quizForm) return;

    // Enable form
    quizForm.style.pointerEvents = 'auto';
    quizForm.style.opacity = '1';

    // Helper for API calls
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

    // Update Navigation Grid UI
    function updateNavGrid() {
        const answeredRadios = document.querySelectorAll('.question-radio:checked');
        const answeredTexts = document.querySelectorAll('.question-text');
        
        // Reset all
        document.querySelectorAll('[id^="nav-btn-"]').forEach(btn => {
            btn.className = "aspect-square rounded-lg flex items-center justify-center text-sm font-bold transition-all border border-slate-200 text-slate-500 hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50";
        });

        // Mark answered radios
        answeredRadios.forEach(radio => {
            const index = radio.dataset.index;
            const btn = document.getElementById(`nav-btn-${index}`);
            if (btn) {
                btn.className = "aspect-square rounded-lg flex items-center justify-center text-sm font-bold transition-all bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30";
            }
        });

        // Mark answered texts (if not empty)
        answeredTexts.forEach(text => {
            if (text.value.trim() !== '') {
                const index = text.dataset.index;
                const btn = document.getElementById(`nav-btn-${index}`);
                if (btn) {
                    btn.className = "aspect-square rounded-lg flex items-center justify-center text-sm font-bold transition-all bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/30";
                }
            }
        });

        // Update counts
        const totalAnswered = answeredRadios.length + Array.from(answeredTexts).filter(t => t.value.trim() !== '').length;
        if (progressText) progressText.textContent = `${totalAnswered}/${totalQuestions}`;
        if (mobileProgress) mobileProgress.textContent = `${totalAnswered}/${totalQuestions} Soal`;
    }

    // Listeners for inputs
    document.querySelectorAll('.question-radio, .question-text').forEach(input => {
        input.addEventListener('change', updateNavGrid);
        input.addEventListener('input', updateNavGrid);
    });
    
    // Initial update
    updateNavGrid();

    // Timer Logic
    function calculateTimeRemaining() {
        if (!hasTimer || timerInitialSeconds === null || timerStartTime === null) {
            return initialTimeRemaining;
        }
        const now = Math.floor(Date.now() / 1000);
        const elapsed = now - timerStartTime;
        return Math.max(0, timerInitialSeconds - elapsed);
    }

    function updateTimerDisplay() {
        if (!hasTimer || !timerElement) return;

        const timeRemaining = calculateTimeRemaining();
        if (timeRemaining === null) return;

        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timeString = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        
        timerElement.textContent = timeString;
        if (mobileTimer) mobileTimer.textContent = timeString;

        // Visual warning for low time
        if (timeRemaining <= 60) {
            timerElement.classList.add('text-red-400');
            timerElement.parentElement.parentElement.classList.add('bg-red-900');
            if (mobileTimer) {
                mobileTimer.classList.remove('text-blue-600', 'bg-blue-50');
                mobileTimer.classList.add('text-red-600', 'bg-red-50');
            }
        } else {
            timerElement.classList.remove('text-red-400');
            timerElement.parentElement.parentElement.classList.remove('bg-red-900');
            if (mobileTimer) {
                mobileTimer.classList.add('text-blue-600', 'bg-blue-50');
                mobileTimer.classList.remove('text-red-600', 'bg-red-50');
            }
        }
    }

    function startTimerInterval() {
        if (!hasTimer || initialTimeRemaining === null) return;

        if (timerStartTime === null) {
            timerStartTime = Math.floor(Date.now() / 1000);
            timerInitialSeconds = initialTimeRemaining;
        }

        clearInterval(timerInterval);
        updateTimerDisplay();

        timerInterval = setInterval(function() {
            if (!quizStarted || quizSubmitting) return;

            const timeRemaining = calculateTimeRemaining();
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                submitQuiz();
                return;
            }
            updateTimerDisplay();
        }, 1000);
    }

    function submitQuiz() {
        if (quizSubmitting) return;
        quizSubmitting = true;
        clearInterval(timerInterval);
        clearTimeout(blurTimeout);
        
        // Show loading state
        Swal.fire({
            title: 'Menyimpan Jawaban...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        quizForm.submit();
    }

    // Warning Logic
    function updateWarningUI() {
        if (!warningCountElement) return;
        warningCountElement.textContent = warningCount;
        
        if (warningCount >= maxWarnings) {
            warningBadge.classList.remove('bg-yellow-50', 'border-yellow-100');
            warningBadge.classList.add('bg-red-50', 'border-red-100');
            warningCountElement.parentElement.classList.remove('bg-yellow-200/50', 'text-yellow-800', 'border-yellow-200');
            warningCountElement.parentElement.classList.add('bg-red-200/50', 'text-red-800', 'border-red-200');
        }
    }

    function logWarning() {
        if (!quizStarted || quizSubmitting) return;

        warningCount = Math.min(warningCount + 1, maxWarnings);
        updateWarningUI();

        if (warningCount >= maxWarnings) {
            submitQuiz();
        }

        callLaravel(endpoints.warning).then(({ ok, data }) => {
            if (ok && data) {
                if (typeof data.warning_count === 'number') warningCount = data.warning_count;
                updateWarningUI();
                if (data.auto_submit) submitQuiz();
            }
        });
    }

    // Event Listeners for Focus/Blur
    window.addEventListener('blur', function() {
        if (!quizStarted || quizSubmitting) return;
        blurStartTime = Date.now();
        logWarning();
        
        clearTimeout(blurTimeout);
        blurTimeout = setTimeout(function() {
            const diff = Date.now() - (blurStartTime || Date.now());
            if (diff >= 10000) submitQuiz();
        }, 10000);
    });

    window.addEventListener('focus', function() {
        if (!quizStarted || quizSubmitting) return;
        clearTimeout(blurTimeout);
        blurStartTime = null;
        
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

    // Submit Confirmation
    quizForm.addEventListener('submit', function(e) {
        if (quizSubmitting) return;
        
        e.preventDefault();
        
        const answered = document.querySelectorAll('.question-radio:checked').length + 
                        Array.from(document.querySelectorAll('.question-text')).filter(t => t.value.trim() !== '').length;
        
        let title = 'Kumpulkan Jawaban?';
        let text = 'Pastikan Anda sudah memeriksa kembali jawaban Anda.';
        let icon = 'question';

        if (answered < totalQuestions) {
            title = 'Masih ada soal kosong!';
            text = `Anda baru menjawab ${answered} dari ${totalQuestions} soal. Yakin ingin mengumpulkan?`;
            icon = 'warning';
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kumpulkan!',
            cancelButtonText: 'Periksa Lagi',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold',
                cancelButton: 'rounded-lg px-6 py-2.5 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitQuiz();
            }
        });
    });

    // Prevent accidental leave
    window.addEventListener('beforeunload', function(e) {
        const timeRemaining = calculateTimeRemaining();
        if (quizStarted && !quizSubmitting && (timeRemaining === null || timeRemaining > 0)) {
            e.preventDefault();
            e.returnValue = 'Progress quiz Anda akan hilang.';
            return e.returnValue;
        }
    });

    // Start everything
    updateWarningUI();
    startTimerInterval();
});
</script>
@endsection

