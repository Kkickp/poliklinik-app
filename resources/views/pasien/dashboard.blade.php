<x-layouts.app title="Dashboard Pasien">

<h3>Dashboard Pasien</h3>

{{-- 🔥 BANNER ANTRIAN --}}
@if($antrianAktif)
<div class="card mb-4 p-3 bg-light">
    <h5>Antrian Aktif</h5>

    <p><b>Poli:</b> {{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli }}</p>
    <p><b>Dokter:</b> {{ $antrianAktif->jadwalPeriksa->dokter->name }}</p>
    <p><b>Jadwal:</b> 
        {{ $antrianAktif->jadwalPeriksa->hari }} 
        ({{ $antrianAktif->jadwalPeriksa->jam_mulai }} - {{ $antrianAktif->jadwalPeriksa->jam_selesai }})
    </p>

    <p><b>No Antrian Anda:</b> {{ $antrianAktif->no_antrian }}</p>

    <p><b>Sedang Dilayani:</b> 
        <span id="current-antrian">
            {{ $antrianAktif->jadwalPeriksa->daftarPolis->where('status','dipanggil')->first()?->no_antrian ?? '-' }}
        </span>
    </p>
</div>
@endif

{{-- 🔥 TABEL JADWAL --}}
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Poli</th>
            <th>Dokter</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Sedang Dilayani</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jadwals as $i => $jadwal)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $jadwal->dokter->poli->nama_poli }}</td>
            <td>{{ $jadwal->dokter->name }}</td>
            <td>{{ $jadwal->hari }}</td>
            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
            <td>
                {{ $jadwal->daftarPolis->where('status','dipanggil')->first()?->no_antrian ?? '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</x-layouts.app>