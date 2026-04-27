<x-layouts.app title="Dashboard Pasien">

<div class="p-6">

    <h2 class="text-2xl font-bold mb-6">Dashboard Pasien</h2>

    {{-- 🔥 BANNER --}}
    @if($antrianAktif)
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 rounded-xl mb-6 shadow">

            <h3 class="text-lg font-semibold mb-2">Antrian Aktif Anda</h3>

            <p><b>Poli:</b> {{ $antrianAktif->jadwalPeriksa->poli->nama }}</p>
            <p><b>Dokter:</b> {{ $antrianAktif->jadwalPeriksa->dokter->nama }}</p>
            <p><b>Jadwal:</b> 
                {{ $antrianAktif->jadwalPeriksa->hari }} 
                ({{ $antrianAktif->jadwalPeriksa->jam_mulai }} - 
                 {{ $antrianAktif->jadwalPeriksa->jam_selesai }})
            </p>

            <div class="flex gap-6 mt-4">

                <div class="bg-white text-blue-600 p-4 rounded-lg text-center w-32">
                    <p class="text-sm">Nomor Anda</p>
                    <p class="text-3xl font-bold" id="nomor-saya">
                        {{ $antrianAktif->no_antrian }}
                    </p>
                </div>

                <div class="bg-white text-indigo-600 p-4 rounded-lg text-center w-32">
                    <p class="text-sm">Sedang Dilayani</p>
                    <p class="text-3xl font-bold" id="sedang-dilayani">
                        {{ $sedangDilayani->no_antrian ?? '-' }}
                    </p>
                </div>

            </div>
        </div>
    @else
        <div class="alert alert-info mb-6">
            Anda belum memiliki antrian aktif.
        </div>
    @endif


    {{-- 🔘 TOMBOL --}}
    <div class="flex justify-end mb-4">
        @if(!$antrianAktif)
            <a href="{{ route('pasien.daftar') }}" class="btn btn-primary">
                + Daftar Poli
            </a>
        @else
            <span class="text-sm text-gray-500">
                Selesaikan antrian terlebih dahulu
            </span>
        @endif
    </div>


    {{-- 📋 TABEL --}}
    <div class="bg-white p-4 rounded-xl shadow">
        <table class="table table-zebra w-full">
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
                @foreach($jadwal as $i => $j)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $j->poli->nama }}</td>
                        <td>{{ $j->dokter->nama }}</td>
                        <td>{{ $j->hari }}</td>
                        <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                        <td>
                            <span class="badge badge-primary">
                                {{ $j->sedang_dilayani }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- ⚡ AJAX --}}
<script>
function fetchAntrian() {
    fetch("{{ route('pasien.antrian.data') }}")
        .then(res => res.json())
        .then(data => {
            const nomorSaya = document.getElementById('nomor-saya');
            const sedangDilayani = document.getElementById('sedang-dilayani');

            if (data.antrian && nomorSaya) {
                nomorSaya.innerText = data.antrian.no_antrian;
            }

            if (sedangDilayani) {
                sedangDilayani.innerText = data.sedang_dilayani ?? '-';
            }
        })
        .catch(err => console.error(err));
}

setInterval(fetchAntrian, 5000);
</script>

</x-layouts.app>