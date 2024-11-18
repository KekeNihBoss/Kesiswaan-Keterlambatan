<x-filament::page>
    <h1>Detail Siswa Terlambat pada Tanggal {{ $tanggal }}</h1>

    @if ($siswaTerlambat->isEmpty())
        <p>Tidak ada siswa yang terlambat pada tanggal ini.</p>
    @else
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama Siswa</th>
                    <th class="px-4 py-2">NIS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswaTerlambat as $keterlambatan)
                    <tr>
                        <td class="border px-4 py-2">{{ $keterlambatan->siswa->nama }}</td>
                        <td class="border px-4 py-2">{{ $keterlambatan->siswa->nis }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-filament::page>
