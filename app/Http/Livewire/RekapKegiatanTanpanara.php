<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Exports\RekapSiswaExport;
use App\Models\MonitoringKegiatan;
use App\Exports\RekapKegiatanExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapKegiatanTanpanara extends Component
{
    public $filterKelas;
    public $tanggalAwal, $tanggalAkhir, $kegiatan_id;
    public $search = '';
    public $siswa;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $kegiatan;
    public $filterTahunAkademik, $kelas, $angkatan_id, $monitoringArray;

    public function mount($kegiatan)
    {
        $this->filterTahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
        $this->kelas = TahunAkademik::find($this->filterTahunAkademik)->kelas;
        $this->kegiatan = $kegiatan;
        $this->kegiatan_id = $kegiatan->id;
        $this->angkatan_id = $this->kelas->first()->angkatan_id;
        // dd($this->kelas);
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->filterKelas = $this->kelas->first()->id;
    }

    public function export()
    {
        // dd('Export');
        $namaKelas = Kelas::select('nama')->where('id', $this->filterKelas)->first()->nama;
        return Excel::download(new RekapKegiatanExport($this->filterKelas, $this->tanggalAwal, $this->tanggalAkhir, $this->kegiatan->id, $this->filterTahunAkademik), 'Rekap Kehadiran ' . $this->kegiatan->nama . ' ' . $namaKelas . ' ' . $this->tanggalAwal . ' - ' . $this->tanggalAkhir . '.xlsx');
    }

    public function updatedFilterTahunAkademik()
    {
        $this->kelas = TahunAkademik::find($this->filterTahunAkademik)->kelas;
        $this->filterKelas = $this->kelas->first()->id;
        $this->angkatan_id = $this->kelas->first()->angkatan_id;
    }

    public function updatedFilterKelas()
    {
        $this->angkatan_id = Kelas::find($this->filterKelas)->angkatan_id;
    }

    public function render()
    {
        //Ambil monitoring pada tahun akademik ini
        $monitoring = MonitoringKegiatan::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan->id)->whereRelation('jadwalKegiatan', 'angkatan_id', $this->angkatan_id)->whereRelation('jadwalKegiatan', 'tahun_akademik_id', $this->filterTahunAkademik)->get();

        $this->monitoringArray = [];
        if (count($monitoring) !== 0) {
            foreach ($monitoring as $m) {
                array_push($this->monitoringArray, $m->id);
            }
        }
        $presensi = Siswa::where('nama', 'like', '%' . $this->search . '%')->whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranKegiatan' => function ($query) {
            $query->whereIn('monitoring_kegiatan_id', $this->monitoringArray);;
        }])->orderBy('nama', 'asc')->paginate(10);
        return view('livewire.rekap-kegiatan-tanpanara', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'dataSiswa' => $presensi,
            'tahunAkademik' => TahunAkademik::all()
        ]);
    }
    public function updatingFilterTahunAkademik()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->angkatan_id = Kelas::find($this->filterKelas)->angkatan_id;
        $this->resetPage();
    }
}
