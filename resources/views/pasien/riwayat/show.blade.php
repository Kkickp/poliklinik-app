<x-layouts.app title="Detail Pemeriksaan">
<div class="container">
    <h4>Detail Pemeriksaan</h4>

    <div class="card p-3">
        <p><strong>Poli:</strong> {{ $data->jadwalPeriksa->dokter->poli->nama_poli }}</p>
        <p><strong>Dokter:</strong> {{ $data->jadwalPeriksa->dokter->name }}</p>
        <p><strong>Tanggal:</strong> {{ $data->created_at->format('d-m-Y') }}</p>

        @php
            $periksa = $data->periksas->first();
        @endphp

        <hr>

        <p><strong>Catatan:</strong> {{ $periksa->catatan }}</p>
        <p><strong>Biaya:</strong> Rp {{ number_format($periksa->biaya_periksa) }}</p>

        <h5>Obat:</h5>
        <ul>
            @foreach($periksa->detailPeriksas as $detail)
                <li>{{ $detail->obat->nama_obat }}</li>
            @endforeach
        </ul>
    </div>
</div>
</x-layouts.app>