<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\MonitoringKegnas;
use App\Exports\RekapSiswaExport;
use App\Exports\RekapKegnasExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapKegiatanNara extends Component
{
    public $filterKelas;
    public $tanggalAwal, $tanggalAkhir, $kegiatan_id, $kegiatan;
    public $search = '';
    public $siswa;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount($kegiatan)
    {
        $this->kegiatan = $kegiatan;
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
    }

    public function export()
    {
        // dd('Export');
        $namaKelas = Kelas::select('nama')->where('id', $this->filterKelas)->first()->nama;
        return Excel::download(new RekapKegnasExport($this->filterKelas, $this->tanggalAwal, $this->tanggalAkhir, $this->kegiatan->id), 'Rekap Kehadiran ' . $this->kegiatan->nama . ' ' . $namaKelas . ' ' . $this->tanggalAwal . ' - ' . $this->tanggalAkhir . '.xlsx');
    }

    public function render()
    {
        $presensi = Siswa::whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranKegnas' => function ($query) {
            $query->where('kegiatan_id', $this->kegiatan->id)->whereRelation('monitoringKegnas', 'tanggal', '>=', $this->tanggalAwal)->whereRelation('monitoringKegnas', 'tanggal', '<=', $this->tanggalAkhir);
        }])->orderBy('nama', 'asc')->paginate(10);
        // dd($presensi[0]);

        return view('livewire.rekap-kegiatan-nara', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'dataSiswa' => $presensi
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
