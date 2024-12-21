<div>
    <h3>Detail Keterlambatan Minggu ke-{{ $minggu_ke }} Tahun {{ $tahun }}</h3>
    <p>
        <strong>Rentang Tanggal:</strong> {{ $startOfWeek }} - {{ $endOfWeek }}
    </p>

    <table class="table-auto w-full border-collapse border border-gray-200 mt-4">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2">Nama Siswa</th>
                <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                <th class="border border-gray-300 px-4 py-2">Waktu</th>
                <th class="border border-gray-300 px-4 py-2">Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($keterlambatan as $data)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $data->siswa->nama ?? 'Tidak Diketahui' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $data->tanggal }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $data->waktu }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $data->alasan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">Tidak ada data keterlambatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
