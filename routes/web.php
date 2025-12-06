<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\MapelController as AdminMapelController;
use App\Http\Controllers\Admin\GamificationController as AdminGamificationController;
use App\Http\Controllers\Admin\InformasiController as AdminInformasiController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Pengajar\DashboardController as PengajarDashboardController;
use App\Http\Controllers\Pengajar\MateriController as PengajarMateriController;
use App\Http\Controllers\Pengajar\QuizController as PengajarQuizController;
use App\Http\Controllers\Pengajar\ResultController as PengajarResultController;
use App\Http\Controllers\Pengajar\FeedbackController as PengajarFeedbackController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\MateriController as SiswaMateriController;
use App\Http\Controllers\Siswa\QuizController as SiswaQuizController;
use App\Http\Controllers\Siswa\QuizSessionController as SiswaQuizSessionController;
use App\Http\Controllers\Siswa\LeaderboardController as SiswaLeaderboardController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
        'pemilik' => redirect()->route('owner.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::resource('kelas', AdminKelasController::class);
    Route::post('/kelas/{id}/assign-pengajar', [AdminKelasController::class, 'assignPengajar'])->name('kelas.assign-pengajar');
    Route::post('/kelas/{id}/unassign-pengajar', [AdminKelasController::class, 'unassignPengajar'])->name('kelas.unassign-pengajar');
    Route::post('/kelas/{id}/assign-siswa', [AdminKelasController::class, 'assignSiswa'])->name('kelas.assign-siswa');
    Route::post('/kelas/{id}/unassign-siswa', [AdminKelasController::class, 'unassignSiswa'])->name('kelas.unassign-siswa');
    Route::resource('mapel', AdminMapelController::class);
    Route::get('/gamification', [AdminGamificationController::class, 'index'])->name('gamification.index');
    Route::post('/gamification', [AdminGamificationController::class, 'store'])->name('gamification.store');
    Route::get('/gamification/{id}/edit', [AdminGamificationController::class, 'edit'])->name('gamification.edit');
    Route::put('/gamification/{id}', [AdminGamificationController::class, 'update'])->name('gamification.update');
    Route::delete('/gamification/{id}', [AdminGamificationController::class, 'destroy'])->name('gamification.destroy');
    Route::resource('informasi', AdminInformasiController::class);
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
    Route::post('/quiz/{id}/start', [SiswaQuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/{id}/attempt', [SiswaQuizController::class, 'attempt'])->name('quiz.attempt');
    Route::post('/quiz/{id}/submit', [SiswaQuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/result/{id}', [SiswaQuizController::class, 'result'])->name('quiz.result');
    Route::post('/quiz/{quiz}/session/pause', [SiswaQuizSessionController::class, 'pause'])->name('quiz.session.pause');
    Route::post('/quiz/{quiz}/session/resume', [SiswaQuizSessionController::class, 'resume'])->name('quiz.session.resume');
    Route::post('/quiz/{quiz}/session/warning', [SiswaQuizSessionController::class, 'warning'])->name('quiz.session.warning');
    Route::get('/leaderboard', [SiswaLeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/progress', [SiswaDashboardController::class, 'progress'])->name('progress');
});

// Owner Routes
Route::middleware(['auth', 'role:pemilik'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/teachers', [\App\Http\Controllers\Owner\TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{id}', [\App\Http\Controllers\Owner\TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/quizzes/{id}', [\App\Http\Controllers\Owner\QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/students', [\App\Http\Controllers\Owner\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [\App\Http\Controllers\Owner\StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{id}/pdf', [\App\Http\Controllers\Owner\StudentController::class, 'downloadPDF'])->name('students.pdf');
});

// Wali Routes
Route::middleware(['auth', 'role:wali'])->prefix('wali')->name('wali.')->group(function () {
    Route::get('/dashboard', [WaliDashboardController::class, 'index'])->name('dashboard');
    Route::get('/nilai', [WaliDashboardController::class, 'nilai'])->name('nilai');
    Route::get('/nilai/download-pdf', [WaliDashboardController::class, 'downloadPDF'])->name('nilai.download-pdf');
    Route::get('/progress', [WaliDashboardController::class, 'progress'])->name('progress');
});
