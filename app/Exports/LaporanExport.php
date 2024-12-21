<?php

namespace App\Exports;

use App\Models\laporan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return laporan::query()->select('id', 'tanggal', 'minggu', 'jumlah_terlambat');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Minggu',
            'Jumlah Siswa Terlambat',
        ];
    }
}
