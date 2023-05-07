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


    public function mount($kegiatan)
    {
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
        return Excel::download(new DaftarKegnasExport($this->filterAngkatan, $this->jml_siswa, $this->tanggalAwal, $this->tanggalAkhir, $this->kegiatan->id), 'Daftar Pertemuan Kegiatan ' . $kegiatan . ' Angkatan ' . $angkatan . ' ' . $this->tanggalAwal . ' sampai ' . $this->tanggalAkhir . '.xlsx');
    }

    // public function updatedFilterKelas()
    // {
    //     $this->arrayMapel = [];
    //     if (Auth::user()->role === 'guru') {
    //         //Ambil Jadwal Guru
    //         $jadwal = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->where('kelas_id', $this->filterKelas)->with(['mataPelajaran' => function ($query) {
    //             $query->select('id');
    //         }])->select('id', 'guru_id', 'mata_pelajaran_id')->get();

    //         //Ambil Id Mata Pelajaran dari setiap jadwal
    //         foreach ($jadwal as $d) {
    //             array_push($this->arrayMapel, $d->mataPelajaran->id);
    //         }
    //         $this->mapel = MataPelajaran::whereIn('id', $this->arrayMapel)->select('id', 'nama')->get();
    //         $this->filterTahunAkademik = $this->mapel->first()->id;
    //     } else {
    //         $this->mapel = MataPelajaran::all();
    //         $this->filterTahunAkademik = $this->mapel->first()->id;
    //     }
    // }

    public function render()
    {
        // dd(MonitoringKegnas::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with('kehadiranKegnas')->get());
        return view('livewire.daftar-kegiatan-nara', [
            'angkatan' => Angkatan::all(),
            'pertemuan' => MonitoringKegnas::where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with('kehadiranKegnas')->whereRelation('jadwalKegiatan', 'angkatan_id', $this->filterAngkatan)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan->id)->paginate(10),
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
