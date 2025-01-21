<div>
    <!-- Statistik Widget -->
    {{ $this->getStats() }}

    <!-- Modal untuk Siswa Terlambat 2 Kali -->
    <div x-data="{ open: false }" id="modal-dua" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-bold mb-4">Siswa Terlambat 2 Kali</h2>
            <ul>
                @foreach ($siswaDuaKali as $siswa)
                    <li>{{ $siswa->nama_siswa }} ({{ $siswa->jumlah_terlambat }} kali)</li>
                @endforeach
            </ul>
            <button x-on:click="open = false" class="mt-4 bg-gray-800 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>

    <!-- Modal untuk Siswa Terlambat 3 Kali -->
    <div x-data="{ open: false }" id="modal-tiga" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-bold mb-4">Siswa Terlambat 3 Kali</h2>
            <ul>
                @foreach ($siswaTigaKali as $siswa)
                    <li>{{ $siswa->nama_siswa }} ({{ $siswa->jumlah_terlambat }} kali)</li>
                @endforeach
            </ul>
            <button x-on:click="open = false" class="mt-4 bg-gray-800 text-white px-4 py-2 rounded">Tutup</button>
        </div>
    </div>
</div>
