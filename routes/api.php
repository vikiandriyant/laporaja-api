<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RiwayatLaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes untuk Authentication
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetToken']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::middleware(['auth:api'])->group(function () {
    // Routes untuk semua authenticated user
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/{id}', [KategoriController::class, 'show']);
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/{id}', [LaporanController::class, 'show']); // Updated: menggunakan ID langsung
    Route::get('/surat', [SuratController::class, 'index']);
    Route::get('/surat/{id}', [SuratController::class, 'show']); // Updated: menggunakan ID langsung
    Route::get('/riwayat-laporan', [RiwayatLaporanController::class, 'index']);
    Route::get('/riwayat-laporan/{id}', [RiwayatLaporanController::class, 'show']); // Updated: menggunakan ID langsung

    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::post('/user/{user}', [UserController::class, 'update']);

    // Routes untuk user biasa (create laporan/surat)
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::post('/surat', [SuratController::class, 'store']);
    Route::post('/riwayat-laporan', [RiwayatLaporanController::class, 'store']);

    // Routes delete untuk hooks dari laporan/surat
    Route::delete('/surat/{surat}', [SuratController::class, 'destroy']);
    Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy']);

    Route::post('/laporan/{laporan}', [LaporanController::class, 'update']);
    Route::post('/surat/{surat}', [SuratController::class, 'update']);
    Route::post('/riwayat-laporan/{id}', [RiwayatLaporanController::class, 'update']);

    // Routes dengan middleware tambahan untuk admin
    Route::middleware('role:admin')->group(function () {
        // Kategori management (admin only)
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::put('/kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy']);

        // Laporan management (admin only)
        Route::post('/riwayat-laporan/{id}/status', [RiwayatLaporanController::class, 'updateStatus']); // Route khusus update status
        Route::delete('/riwayat-laporan/{id}', [RiwayatLaporanController::class, 'destroy']);

        // User management (admin only)
        Route::delete('/user/{user}', [UserController::class, 'destroy']);
    });
});
