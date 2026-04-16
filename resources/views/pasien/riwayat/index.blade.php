<x-layouts.app title="Riwayat Pendaftaran">
<div class="container">
    <h4>Riwayat Pendaftaran Poli</h4>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Tanggal</th>
                <th>No Antrian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->jadwalPeriksa->dokter->poli->nama_poli }}</td>
                <td>{{ $item->jadwalPeriksa->dokter->name }}</td>
                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                <td>{{ $item->no_antrian }}</td>
                <td>
                    @if($item->periksas->count() > 0)
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">Menunggu</span>
                    @endif
                </td>
                <td>
                    @if($item->periksas->count() > 0)
                        <a href="{{ route('pasien.riwayat.detail', $item->id) }}" class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-layouts.app>