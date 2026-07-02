<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraumaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Pakar Deteksi Dini Trauma Pertumbuhan Anak
|--------------------------------------------------------------------------
| Semua state (jawaban & hasil) disimpan di session, tidak ada database.
*/

Route::get('/', [TraumaController::class, 'welcome'])->name('welcome');

Route::get('/kuisioner', [TraumaController::class, 'showQuiz'])->name('quiz.show');
Route::post('/kuisioner', [TraumaController::class, 'submitQuiz'])->name('quiz.submit');

Route::get('/hasil', [TraumaController::class, 'showResult'])->name('result.show');

Route::get('/ulangi', [TraumaController::class, 'reset'])->name('quiz.reset');