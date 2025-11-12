<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\MapelController as AdminMapelController;
use App\Http\Controllers\Admin\GamificationController as AdminGamificationController;
use App\Http\Controllers\Pengajar\DashboardController as PengajarDashboardController;
use App\Http\Controllers\Pengajar\MateriController as PengajarMateriController;
use App\Http\Controllers\Pengajar\QuizController as PengajarQuizController;
use App\Http\Controllers\Pengajar\ResultController as PengajarResultController;
use App\Http\Controllers\Pengajar\FeedbackController as PengajarFeedbackController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\MateriController as SiswaMateriController;
use App\Http\Controllers\Siswa\QuizController as SiswaQuizController;
use App\Http\Controllers\Siswa\LeaderboardController as SiswaLeaderboardController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;

// Home
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard redirect
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'pengajar' => redirect()->route('pengajar.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        'wali' => redirect()->route('wali.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::resource('kelas', AdminKelasController::class);
    Route::resource('mapel', AdminMapelController::class);
    Route::get('/gamification', [AdminGamificationController::class, 'index'])->name('gamification.index');
    Route::post('/gamification', [AdminGamificationController::class, 'store'])->name('gamification.store');
    Route::put('/gamification/{id}', [AdminGamificationController::class, 'update'])->name('gamification.update');
    Route::delete('/gamification/{id}', [AdminGamificationController::class, 'destroy'])->name('gamification.destroy');
});

// Pengajar Routes
Route::middleware(['auth', 'role:pengajar'])->prefix('pengajar')->name('pengajar.')->group(function () {
    Route::get('/dashboard', [PengajarDashboardController::class, 'index'])->name('dashboard');
    Route::resource('materi', PengajarMateriController::class);
    Route::resource('quiz', PengajarQuizController::class);
    Route::post('/quiz/{quizId}/questions', [PengajarQuizController::class, 'storeQuestion'])->name('quiz.questions.store');
    Route::delete('/quiz/{quizId}/questions/{questionId}', [PengajarQuizController::class, 'destroyQuestion'])->name('quiz.questions.destroy');
    Route::get('/results', [PengajarResultController::class, 'index'])->name('results.index');
    Route::get('/results/{id}', [PengajarResultController::class, 'show'])->name('results.show');
    Route::get('/feedback/create/{resultId?}', [PengajarFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [PengajarFeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback', [PengajarFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/{id}/edit', [PengajarFeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{id}', [PengajarFeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{id}', [PengajarFeedbackController::class, 'destroy'])->name('feedback.destroy');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/materi', [SiswaMateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{id}', [SiswaMateriController::class, 'show'])->name('materi.show');
    Route::get('/quiz', [SiswaQuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/{id}', [SiswaQuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{id}/submit', [SiswaQuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/result/{id}', [SiswaQuizController::class, 'result'])->name('quiz.result');
    Route::get('/leaderboard', [SiswaLeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/progress', [SiswaDashboardController::class, 'progress'])->name('progress');
});

// Wali Routes
Route::middleware(['auth', 'role:wali'])->prefix('wali')->name('wali.')->group(function () {
    Route::get('/dashboard', [WaliDashboardController::class, 'index'])->name('dashboard');
    Route::get('/nilai', [WaliDashboardController::class, 'nilai'])->name('nilai');
    Route::get('/progress', [WaliDashboardController::class, 'progress'])->name('progress');
});
