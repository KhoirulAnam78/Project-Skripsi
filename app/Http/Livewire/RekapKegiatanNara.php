<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Exports\RekapSiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapKegiatanNara extends Component
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
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
    }

    // public function export()
    // {
    //     $namaKelas = Kelas::select('nama')->where('id', $this->filterKelas)->first()->nama;
    //     return Excel::download(new RekapSiswaExport($this->filterKelas, $this->tanggalAwal, $this->tanggalAkhir), 'Rekap Kehadiran Siswa ' . $namaKelas . 'Tanggal ' . $this->tanggalAwal . ' Sampai ' . $this->tanggalAkhir . '.xlsx');
    // }

    public function render()
    {
        return view('livewire.rekap-kegiatan-nara', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'dataSiswa' => Siswa::whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranKegnas' => function ($query) {
                $query->whereRelation('monitoringKegnas', 'tanggal', '>=', $this->tanggalAwal)->whereRelation('monitoringKegnas', 'tanggal', '<=', $this->tanggalAkhir);
            }])->where('nama', 'like', '%' . $this->search . '%')->orderBy('nama', 'asc')->paginate(10)
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
