<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Exports\RekapSiswaExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RekapSiswa extends Component
{
    public $filterKelas;
    public $tanggalAwal, $tanggalAkhir;
    public $search = '';
    public $siswa;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        //ambil kelas
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->filterKelas = '';
        if (TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()) {
            $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;
        }
    }

    public function export()
    {
        $namaKelas = Kelas::select('nama')->where('id', $this->filterKelas)->first()->nama;
        return Excel::download(new RekapSiswaExport($this->filterKelas, $this->tanggalAwal, $this->tanggalAkhir), 'Rekap Kehadiran Siswa ' . $namaKelas . 'Tanggal ' . $this->tanggalAwal . ' Sampai ' . $this->tanggalAkhir . '.xlsx');
    }

    public function render()
    {
        $dataSiswa = DB::table('siswas as a')
                    ->where('nama', 'like', '%' . $this->search . '%')
                    ->leftjoin('kelas_siswa as b','a.id','b.siswa_id')
                    ->where('b.kelas_id',$this->filterKelas)
                    ->leftjoin('kehadiran_pembelajarans as c','c.siswa_id','a.id')
                    ->leftjoin('monitoring_pembelajaran_news as d','d.monitoring_pembelajaran_id','c.monitoring_pembelajaran_id')
                    ->where('d.tanggal', '>=', $this->tanggalAwal)
                    ->where('d.tanggal', '<=', $this->tanggalAkhir)
                    ->groupBy('a.id')
                    ->select('a.nisn','a.nama',
                        DB::raw("SUM(CASE WHEN c.status = 'hadir' THEN 1 ELSE 0 END) AS hadir"),
                        DB::raw("SUM(CASE WHEN c.status = 'izin' THEN 1 ELSE 0 END) AS izin"),
                        DB::raw("SUM(CASE WHEN c.status = 'sakit' THEN 1 ELSE 0 END) AS sakit"),
                        DB::raw("SUM(CASE WHEN c.status = 'alfa' THEN 1 ELSE 0 END) AS alfa"),
                        DB::raw("SUM(CASE WHEN c.status = 'dinas dalam' THEN 1 ELSE 0 END) AS dd"),
                        DB::raw("SUM(CASE WHEN c.status = 'dinas luar' THEN 1 ELSE 0 END) AS dl"),
                    )
                    ->orderBy('a.nama')
                    ->distinct()
                    ->paginate(10);
        return view('livewire.rekap-siswa', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'dataSiswa' => $dataSiswa
        ]);
    }
    public function updatingFilterTahunAkademik()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }
}
