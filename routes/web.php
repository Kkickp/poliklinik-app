<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\PeriksaController;
use App\Http\Controllers\Pasien\PasienDashboardController;
use App\Http\Controllers\Pasien\RiwayatController;

// | Web Routes

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes untuk admin

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::resource('polis', PoliController::class);
        Route::resource('dokter', DokterController::class);
        Route::resource('pasien', PasienController::class);
        Route::resource('obat', ObatController::class);
    });

    // Routes untuk dokter

Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dokter.dashboard');
        })->name('dokter.dashboard');

        Route::post('/periksa/store', [PeriksaController::class, 'store'])
            ->name('periksa.store');
    });

    // Routes untuk pasien

Route::middleware(['auth'])->group(function () {

    Route::get('/pasien/dashboard', [PasienDashboardController::class, 'index'])
        ->name('pasien.dashboard');

    Route::get('/pasien/riwayat', [RiwayatController::class, 'index'])
        ->name('pasien.riwayat');

    Route::get('/pasien/riwayat/{id}', [RiwayatController::class, 'show'])
        ->name('pasien.riwayat.detail');
});