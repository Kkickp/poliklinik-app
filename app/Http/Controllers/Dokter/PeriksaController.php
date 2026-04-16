<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Periksa;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\DaftarPoli;

class PeriksaController extends Controller
{
    /**
     * Simpan hasil pemeriksaan
     */
    public function store(Request $request)
    {
    $request->validate([
        'daftar_poli_id' => 'required',
        'obat_ids' => 'required|array|min:1'
    ]);

    try {
        DB::transaction(function () use ($request) {

            // 1. Buat periksa
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->daftar_poli_id,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => 0
            ]);

            $totalObat = 0;

            foreach ($request->obat_ids as $obat_id) {

                $obat = Obat::lockForUpdate()->find($obat_id);

                // ❌ jika obat tidak ada / stok habis
                if (!$obat) {
                    throw new \Exception("Obat tidak ditemukan");
                }

                if ($obat->stok <= 0) {
                    throw new \Exception("Stok obat {$obat->nama_obat} habis!");
                }

                // ✔️ kurangi stok
                $obat->decrement('stok');

                // ✔️ simpan detail
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat_id
                ]);

                $totalObat += $obat->harga;
            }

            // 3. update biaya
            $periksa->update([
                'biaya_periksa' => $totalObat + 150000
            ]);

            // 4. pembayaran
            Pembayaran::create([
                'periksa_id' => $periksa->id,
                'status' => 'pending'
            ]);
        });

        return back()->with('success', 'Pemeriksaan berhasil disimpan');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
    }
}