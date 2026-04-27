<?php

namespace App\Http\Controllers\pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class PasienDashboardController extends Controller
{
    // ✅ DASHBOARD VIEW
    public function index()
    {
        $user = Auth::user();

        $antrianAktif = DaftarPoli::with('jadwalPeriksa.dokter', 'jadwalPeriksa.poli')
            ->where('id_pasien', $user->id)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->latest()
            ->first();

        $sedangDilayani = null;

        if ($antrianAktif) {
            $sedangDilayani = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->where('status', 'dipanggil')
                ->orderBy('no_antrian')
                ->first();
        }

        // 📋 SEMUA JADWAL
        $jadwal = JadwalPeriksa::with('dokter', 'poli')->get();

        foreach ($jadwal as $j) {
            $j->sedang_dilayani = DaftarPoli::where('id_jadwal', $j->id)
                ->where('status', 'dipanggil')
                ->orderBy('no_antrian')
                ->first()?->no_antrian ?? '-';
        }

        return view('pasien.dashboard', compact(
            'antrianAktif',
            'sedangDilayani',
            'jadwal'
        ));
    }

    // 🔥 API UNTUK AJAX (POLLING)
    public function getAntrian()
    {
        $user = Auth::user();

        $antrianAktif = DaftarPoli::where('id_pasien', $user->id)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->latest()
            ->first();

        $sedangDilayani = null;

        if ($antrianAktif) {
            $sedangDilayani = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->where('status', 'dipanggil')
                ->orderBy('no_antrian')
                ->first();
        }

        return response()->json([
            'antrian' => $antrianAktif ? [
                'no_antrian' => $antrianAktif->no_antrian,
                'status' => $antrianAktif->status,
            ] : null,
            'sedang_dilayani' => $sedangDilayani->no_antrian ?? null
        ]);
    }
}