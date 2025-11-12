@extends('layouts.app')

@section('title', $quiz->judul)

@section('content')
<!-- Header Card -->
<div class="mb-6 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
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
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" id="progressText">0/{{ $quiz->questions->count() }}</span>
        </div>
        @if($quiz->durasi)
            <div class="flex items-center gap-2 px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg border-2 border-indigo-300 dark:border-indigo-700">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-lg font-bold text-indigo-700 dark:text-indigo-300" id="timer">00:00</span>
            </div>
        @endif
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" id="progressBar" style="width: 0%"></div>
    </div>
</div>

<!-- Quiz Form -->
<div class="bg-white dark:bg-gray-800 border border-gray-200 rounded-xl shadow-lg dark:border-gray-700 overflow-hidden">
    <form id="quizForm" action="{{ route('siswa.quiz.submit', $quiz->id) }}" method="POST">
        @csrf
        <input type="hidden" name="start_time" value="{{ now()->timestamp }}">
        
        @foreach($quiz->questions as $index => $question)
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-0 bg-gray-50 dark:bg-gray-800/50">
                <!-- Question Header -->
                <div class="mb-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-500 text-white font-bold text-lg shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 leading-tight">
                                {{ $question->pertanyaan }}
                            </h3>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                            {{ $question->skor }} poin
                        </span>
                    </div>
                </div>

                <!-- Answer Options -->
                @if($question->tipe == 'pilihan_ganda')
                    <div class="space-y-3">
                        @foreach(is_array($question->pilihan) ? $question->pilihan : [] as $key => $pilihan)
                            <label for="question_{{ $question->id }}_{{ $key }}" 
                                   class="flex items-center p-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200 group has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-100 dark:has-[:checked]:bg-indigo-900/40 dark:has-[:checked]:border-indigo-400 shadow-sm hover:shadow-md">
                                <input type="radio" 
                                       id="question_{{ $question->id }}_{{ $key }}" 
                                       name="jawaban[{{ $question->id }}]" 
                                       value="{{ $key }}"
                                       class="w-5 h-5 text-indigo-600 bg-white border-gray-300 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 question-radio"
                                       data-question="{{ $question->id }}">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 font-semibold text-sm group-hover:bg-indigo-500 group-hover:text-white dark:group-hover:bg-indigo-500 dark:group-hover:text-white transition-colors has-[:checked]:bg-indigo-500 has-[:checked]:text-white dark:has-[:checked]:bg-indigo-500 dark:has-[:checked]:text-white">
                                            {{ $key }}
                                        </span>
                                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $pilihan }}</span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 has-[:checked]:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div>
                        <textarea name="jawaban[{{ $question->id }}]" 
                                  rows="6"
                                  class="block w-full p-4 text-sm text-gray-900 bg-white dark:bg-gray-700 rounded-lg border-2 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-400 transition-all shadow-sm"
                                  placeholder="Tulis jawaban Anda di sini..."></textarea>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Submit Section -->
        <div class="p-6 bg-gray-200 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <a href="{{ route('siswa.quiz.index') }}" 
                   class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-700 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-indigo-900/30 dark:hover:border-indigo-500 dark:hover:text-indigo-300 transition-all shadow-sm hover:shadow-md">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </span>
                </a>
                <button type="submit" 
                        class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-lg hover:from-indigo-700 hover:to-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 border-2 border-gray-300 dark:border-gray-600">
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

<!-- Modal Petunjuk & Doa (SweetAlert2 Style) -->
<div id="instructionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden" style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
    <div class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>
    <div class="relative bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg rounded-2xl shadow-2xl w-full max-w-lg mx-4 border border-white/20 dark:border-gray-700/50 animate-scale-in" style="animation: scaleIn 0.3s ease-out;">
        <div class="p-6">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 mb-3 shadow-lg animate-bounce-subtle">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Petunjuk Pengerjaan Quiz</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm truncate">{{ $quiz->judul }}</p>
            </div>

            <!-- Petunjuk -->
            <div class="mb-5 space-y-3">
                <div class="bg-blue-50/80 dark:bg-blue-900/30 backdrop-blur-sm border-l-4 border-blue-500 p-3 rounded-r-lg shadow-sm">
                    <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Petunjuk Umum
                    </h3>
                    <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-1.5 list-disc list-inside">
                        <li>Bacalah setiap soal dengan teliti sebelum menjawab</li>
                        <li>Pilih jawaban yang paling tepat untuk setiap pertanyaan</li>
                        <li>Anda dapat mengubah jawaban sebelum menekan tombol Submit</li>
                        @if($quiz->durasi)
                            <li>Waktu pengerjaan: <strong class="text-blue-900 dark:text-blue-100">{{ $quiz->durasi }} menit</strong></li>
                            <li>Quiz akan otomatis tersubmit ketika waktu habis</li>
                        @endif
                        <li>Pastikan semua jawaban sudah diisi sebelum submit</li>
                    </ul>
                </div>

                <div class="bg-green-50/80 dark:bg-green-900/30 backdrop-blur-sm border-l-4 border-green-500 p-3 rounded-r-lg shadow-sm">
                    <h3 class="font-semibold text-green-900 dark:text-green-300 mb-2 flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Tips Sukses
                    </h3>
                    <ul class="text-xs text-green-800 dark:text-green-200 space-y-1.5 list-disc list-inside">
                        <li>Kelola waktu dengan baik</li>
                        <li>Jawab soal yang mudah terlebih dahulu</li>
                        <li>Periksa kembali jawaban sebelum submit</li>
                        <li>Tenang dan percaya diri</li>
                    </ul>
                </div>
            </div>

            <!-- Doa -->
            <div class="bg-indigo-50/80 dark:bg-indigo-900/30 backdrop-blur-sm border-l-4 border-indigo-500 p-3 rounded-r-lg mb-5 shadow-sm">
                <h3 class="font-semibold text-indigo-900 dark:text-indigo-300 mb-2 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5 8.85V13a2 2 0 002 2h6a2 2 0 002-2V8.85l2.394-1.93a1 1 0 000-1.84l-7-3z"></path>
                    </svg>
                    Doa Sebelum Belajar
                </h3>
                <div class="text-xs text-indigo-800 dark:text-indigo-200 space-y-1.5 text-right bg-white/50 dark:bg-gray-800/50 p-3 rounded-lg">
                    <p class="font-bold text-sm text-indigo-900 dark:text-indigo-100">رَبِّ زِدْنِي عِلْمًا</p>
                    <p class="italic">"Robbi zidni 'ilman"</p>
                    <p class="text-indigo-700 dark:text-indigo-300">"Ya Allah, tambahkanlah kepadaku ilmu pengetahuan"</p>
                </div>
            </div>

            <!-- Button -->
            <div class="flex justify-center">
                <button id="startQuizBtn" class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl hover:from-indigo-700 hover:to-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 active:scale-95">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mulai Quiz
                    </span>
                </button>
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
    let timerInterval = null;
    let timeRemaining = {{ $quiz->durasi ? $quiz->durasi * 60 : 0 }};
    let quizStarted = false;
    
    // Show modal on load
    instructionModal.classList.remove('hidden');
    quizForm.style.pointerEvents = 'none';
    quizForm.style.opacity = '0.5';
    
    // Start quiz function
    function startQuiz() {
        instructionModal.classList.add('hidden');
        quizForm.style.pointerEvents = 'auto';
        quizForm.style.opacity = '1';
        quizStarted = true;
        
        // Start timer if duration exists
        @if($quiz->durasi)
            startTimer();
        @endif
        
        // Update start time
        document.querySelector('input[name="start_time"]').value = Math.floor(Date.now() / 1000);
    }
    
    // Timer function
    function startTimer() {
        const timerElement = document.getElementById('timer');
        
        timerInterval = setInterval(function() {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert('Waktu pengerjaan telah habis! Quiz akan otomatis tersubmit.');
                quizForm.submit();
                return;
            }
            
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            timerElement.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            // Change color when time is running out
            if (timeRemaining <= 60) {
                timerElement.parentElement.classList.remove('bg-indigo-100', 'border-indigo-300');
                timerElement.parentElement.classList.add('bg-red-100', 'border-red-300', 'dark:bg-red-900/30', 'dark:border-red-700');
                timerElement.classList.remove('text-indigo-700');
                timerElement.classList.add('text-red-700', 'dark:text-red-300');
            } else if (timeRemaining <= 300) {
                timerElement.parentElement.classList.remove('bg-indigo-100', 'border-indigo-300');
                timerElement.parentElement.classList.add('bg-yellow-100', 'border-yellow-300', 'dark:bg-yellow-900/30', 'dark:border-yellow-700');
                timerElement.classList.remove('text-indigo-700');
                timerElement.classList.add('text-yellow-700', 'dark:text-yellow-300');
            }
            
            timeRemaining--;
        }, 1000);
    }
    
    // Start quiz button
    startQuizBtn.addEventListener('click', startQuiz);
    
    // Update progress function
    function updateProgress() {
        const answered = document.querySelectorAll('.question-radio:checked').length;
        const progress = (answered / totalQuestions) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = answered + '/' + totalQuestions;
    }
    
    // Update progress on radio change
    document.querySelectorAll('.question-radio').forEach(radio => {
        radio.addEventListener('change', updateProgress);
    });
    
    // Initial progress update
    updateProgress();
    
    // Form validation before submit
    quizForm.addEventListener('submit', function(e) {
        if (!quizStarted) {
            e.preventDefault();
            return;
        }
        
        const answered = document.querySelectorAll('.question-radio:checked').length;
        if (answered < totalQuestions) {
            if (!confirm('Anda belum menjawab semua soal. Yakin ingin submit?')) {
                e.preventDefault();
            }
        }
        
        // Clear timer on submit
        if (timerInterval) {
            clearInterval(timerInterval);
        }
    });
    
    // Prevent form submission if quiz not started
    quizForm.addEventListener('submit', function(e) {
        if (!quizStarted) {
            e.preventDefault();
        }
    });
    
    // Warn before leaving page
    window.addEventListener('beforeunload', function(e) {
        if (quizStarted && timeRemaining > 0) {
            e.preventDefault();
            e.returnValue = 'Apakah Anda yakin ingin meninggalkan halaman? Progress quiz Anda akan hilang.';
            return e.returnValue;
        }
    });
});
</script>
@endsection

