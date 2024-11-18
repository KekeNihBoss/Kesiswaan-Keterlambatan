<?php
namespace App\Filament\Resources\LaporanResource\Pages;

use App\Models\Keterlambatan;
use App\Filament\Resources\LaporanResource; 
use App\Models\Siswa; // Ganti dengan model siswa yang sesuai
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class DetailLaporan extends Page
{
    protected static string $resource = LaporanResource::class;

    public $tanggal;
    public $siswaTerlambat;

    public function mount(string $tanggal)
    {
        $this->tanggal = $tanggal;
        // Ambil data siswa yang terlambat pada tanggal tertentu
        $this->siswaTerlambat = Keterlambatan::where('tanggal', $tanggal)
            ->with('siswa') // Asumsi keterlambatan ada relasi dengan siswa
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.resources.laporan-resource.pages.detail-laporan', [
            'siswaTerlambat' => $this->siswaTerlambat,
            'tanggal' => $this->tanggal,  // Pass the date to the view as well
        ]);
    }
}
