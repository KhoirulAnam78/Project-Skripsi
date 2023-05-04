<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Exports\RekapSiswaExport;
use App\Exports\RekapKegiatanExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapKegiatanTanpanara extends Component
{
    public $filterKelas;
    public $tanggalAwal, $tanggalAkhir;
    public $search = '';
    public $siswa;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $kegiatan;

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
        return Excel::download(new RekapKegiatanExport($this->filterKelas, $this->tanggalAwal, $this->tanggalAkhir, $this->kegiatan->id), 'Rekap Kehadiran ' . $this->kegiatan->nama . ' ' . $namaKelas . ' ' . $this->tanggalAwal . ' - ' . $this->tanggalAkhir . '.xlsx');
    }

    public function render()
    {

        $presensi = Siswa::whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranKegiatan' => function ($query) {
            $query->where('kegiatan_id', $this->kegiatan->id)->whereRelation('monitoringKegiatan', 'tanggal', '>=', $this->tanggalAwal)->whereRelation('monitoringKegiatan', 'tanggal', '<=', $this->tanggalAkhir);
        }])->orderBy('nama', 'asc')->paginate(10);
        return view('livewire.rekap-kegiatan-tanpanara', [
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
