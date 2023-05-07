<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use App\Models\Angkatan;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\KehadiranKegnas;
use App\Models\MonitoringKegnas;
use App\Exports\DaftarKegnasExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;
use App\Models\MonitoringPembelajaran;

class DaftarKegiatanNara extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $mapel;
    // public $filterKelas = '';
    public $kelas;
    public $kegiatan;
    public $filterAngkatan = '';
    public $detail = [];
    public $jml_siswa;
    public $monitoring;
    public $keterangan;
    public $tanggalAwal;
    public $tanggalAkhir;
    public $filterTahunAkademik;
    public $angkatan;


    public function mount($kegiatan)
    {
        $this->filterTahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
        $this->angkatan = Angkatan::where('status', 'belum lulus')->get();
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->kegiatan = $kegiatan;
        if (Auth::user()->role === 'wali_asrama') {
            $this->filterAngkatan = Auth::user()->waliAsrama->angkatans->first()->id;
            // dd($this->filterAngkatan);
        } else {
            $this->filterAngkatan = Angkatan::select('id')->first()->id;
        }
        $this->jml_siswa = 0;
        $kelas = Kelas::where('angkatan_id', $this->filterAngkatan)->select('id')->get()->all();
        foreach ($kelas as $k) {
            $this->jml_siswa = $this->jml_siswa + $k->siswas->count();
        }
        // $this->kelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas;
        // $this->filterKelas = $this->kelas->first()->id;
    }

    public function updatedFilterangkatan()
    {
        $this->jml_siswa = 0;
        $kelas = Kelas::where('angkatan_id', $this->filterAngkatan)->select('id')->get()->all();
        foreach ($kelas as $k) {
            $this->jml_siswa = $this->jml_siswa + $k->siswas->count();
        }
    }

    public function detail($id)
    {
        // dd('Masuk Sini');
        //ambil data
        // $this->monitoring = $id);
        //ambil data kehadiran siswa yang sudah diinputkan
        $this->detail = KehadiranKegnas::where('monitoring_kegnas_id', $id)->where('status', '!=', 'hadir')->get()->all();
        // dd($this->detail);

        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function empty()
    {
        $this->detail = [];
    }

    public function export()
    {
        $kegiatan = $this->kegiatan->nama;
        $angkatan = Angkatan::find($this->filterAngkatan)->nama;
        return Excel::download(new DaftarKegnasExport($this->filterAngkatan, $this->jml_siswa, $this->tanggalAwal, $this->tanggalAkhir, $this->kegiatan->id, $this->filterTahunAkademik), 'Daftar Pertemuan Kegiatan ' . $kegiatan . ' Angkatan ' . $angkatan . ' ' . $this->tanggalAwal . ' sampai ' . $this->tanggalAkhir . '.xlsx');
    }

    public function updatedFilterTahunAkademik()
    {
        $statusTahunAkademik = TahunAkademik::find($this->filterTahunAkademik)->status;
        if ($statusTahunAkademik === 'aktif') {
            $this->angkatan = Angkatan::where('status', 'belum lulus')->get();
            $this->filterAngkatan = $this->angkatan->first()->id;
        } else {
            $this->angkatan = Angkatan::get();
            $this->filterAngkatan = $this->angkatan->first()->id;
        }
    }

    public function render()
    {
        return view('livewire.daftar-kegiatan-nara', [
            'tahunAkademik' => TahunAkademik::all(),
            'angkatan' => $this->angkatan,
            'pertemuan' => MonitoringKegnas::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with('kehadiranKegnas')->whereRelation('jadwalKegiatan', 'angkatan_id', $this->filterAngkatan)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan->id)->whereRelation('jadwalKegiatan', 'tahun_akademik_id', $this->filterTahunAkademik)->paginate(10),
            'jml_siswa' => $this->jml_siswa,
            'detail' => $this->detail,
            'akademik_id' => TahunAkademik::where('status', 'aktif')->first()->id
            // 'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
        ]);
    }

    public function updatingFilterAngkatan()
    {
        $this->resetPage();
    }
}
