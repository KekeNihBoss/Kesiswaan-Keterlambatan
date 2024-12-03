<div>
    <h3 class="text-lg font-bold">Detail Keterlambatan pada {{ $tanggal }}</h3>
    <table class="table-auto w-full mt-4">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Nama Siswa</th>
                <th class="px-4 py-2 border">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($keterlambatan as $data)
                <tr>
                    <td class="px-4 py-2 border">{{ $data->siswa->nama ?? 'Tidak Diketahui' }}</td>
                    <td class="px-4 py-2 border">{{ $data->keterangan ?? 'Tidak ada keterangan' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center px-4 py-2 border">Tidak ada data keterlambatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
