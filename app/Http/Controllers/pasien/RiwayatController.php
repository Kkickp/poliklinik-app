<?php

namespace App\Http\Controllers\pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DaftarPoli;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
    $riwayat = DaftarPoli::with([
        'jadwalPeriksa.dokter.poli',
        'periksas' // nanti kita ambil status dari sini
    ])
    ->where('id_pasien', Auth::id())
    ->latest()
    ->get();

    return view('pasien.riwayat.index', compact('riwayat'));
    }

    public function show($id)
    {
    $data = DaftarPoli::with([
        'jadwalPeriksa.dokter.poli',
        'periksas.detailPeriksas.obat'
    ])
    ->where('id_pasien', auth()->id())
    ->findOrFail($id);

    return view('pasien.riwayat.show', compact('data'));
    }
}
