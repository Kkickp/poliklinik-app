<?php

namespace App\Http\Controllers\pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class PasienDashboardController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    // 🔥 ambil antrian aktif pasien (yang belum selesai)
    $antrianAktif = DaftarPoli::with('jadwalPeriksa.dokter.poli')
        ->where('id_pasien', $userId)
        ->where('status', '!=', 'selesai')
        ->latest()
        ->first();

    // 🔥 semua jadwal + nomor dilayani
    $jadwals = JadwalPeriksa::with('dokter.poli')
        ->withCount([
            'daftarPolis as current_antrian' => function ($q) {
                $q->where('status', 'dipanggil');
            }
        ])
        ->get();

    return view('pasien.dashboard', compact('antrianAktif', 'jadwals'));
}
}
