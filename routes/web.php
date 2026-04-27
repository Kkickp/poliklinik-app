<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\PeriksaController;
use App\Http\Controllers\Pasien\PasienDashboardController;
use App\Http\Controllers\Pasien\RiwayatController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;

Route::get('/', function () {
    return redirect()->route('login');
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
        Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    });

    // Routes untuk pasien
Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->group(function () {

    Route::get('/dashboard', [PasienDashboardController::class, 'index'])
        ->name('pasien.dashboard');

    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->name('pasien.riwayat');

    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])
        ->name('pasien.riwayat.detail');

    Route::get('/pasien/antrian/data', [PasienDashboardController::class, 'getAntrian'])
        ->name('pasien.antrian.data');

    Route::get('/daftar', [PasienPoliController::class, 'get'])
        ->name('pasien.daftar');

    Route::post('/daftar', [PasienPoliController::class, 'submit'])
        ->name('pasien.daftar.submit');
});