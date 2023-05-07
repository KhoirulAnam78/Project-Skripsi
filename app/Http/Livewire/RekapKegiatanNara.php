<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\MonitoringKegnas;
use App\Exports\RekapKegnasExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapKegiatanNara extends Component
{
    public $filterKelas;
    public $tanggalAwal, $tanggalAkhir, $kegiatan_id, $kegiatan;
    public $search = '';
    public $siswa, $angkatan_id, $filterTahunAkademik;
    use WithPagination;
    public $kelas;
    public $monitoringArray;
    protected $paginationTheme = 'bootstrap';

    public function mount($kegiatan)
    {
        $this->filterTahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
        $this->kelas = TahunAkademik::find($this->filterTahunAkademik)->kelas;
        $this->kegiatan = $kegiatan;
        $this->angkatan_id = $this->kelas->first()->angkatan_id;
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->filterKelas = $this->kelas->first()->id;
    }

    public function export()
    {
        // dd('Export');
        $namaKelas = Kelas::select('nama')->where('id', $this->filterKelas)->first()->nama;
        return Excel::download(new RekapKegnasExport($this->filterKelas, $this->tanggalAwal, $this->tanggalAkhir, $this->kegiatan->id), 'Rekap Kehadiran ' . $this->kegiatan->nama . ' ' . $namaKelas . ' ' . $this->tanggalAwal . ' - ' . $this->tanggalAkhir . '.xlsx');
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
        $monitoring = MonitoringKegnas::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan_id)->whereRelation('jadwalKegiatan', 'angkatan_id', $this->angkatan_id)->whereRelation('jadwalKegiatan', 'tahun_akademik_id', $this->filterTahunAkademik)->get();
        $this->monitoringArray = [];
        if (count($monitoring) !== 0) {
            foreach ($monitoring as $m) {
                array_push($this->monitoringArray, $m->id);
            }
        }
        // dd($monitoring);
        //kemudian ambil setiap siswa dengan kehadiran kegiatan dimana monitoring id nya terdaftar
        $presensi = Siswa::where('nama', 'like', '%' . $this->search . '%')->whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranKegnas' => function ($query) {
            $query->whereIn('monitoring_kegnas_id', $this->monitoringArray);
        }])->orderBy('nama', 'asc')->paginate(10);



        // $presensi = Siswa::whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranKegnas' => function ($query) {
        //     $query->where('kegiatan_id', $this->kegiatan->id)->whereRelation('monitoringKegnas', 'tanggal', '>=', $this->tanggalAwal)->whereRelation('monitoringKegnas', 'tanggal', '<=', $this->tanggalAkhir);
        // }])->orderBy('nama', 'asc')->paginate(10);
        // dd($presensi[0]);


        return view('livewire.rekap-kegiatan-nara', [
            'kelas' => $this->kelas,
            'tahunAkademik' => TahunAkademik::all(),
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
