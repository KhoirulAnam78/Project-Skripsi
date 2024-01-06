<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;
use App\Models\MonitoringPembelajaran;
use App\Models\MonitoringPembelajaranNew;

class DaftarPertemuan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $mapel;
    public $filterTahunAkademik;

    public $tanggalAwal;
    public $tanggalAkhir;
    public $filterKelas = '';
    public $filterMapel = null;
    public $presensi = [];
    public $keterangan;
    public $arrayMapel = [];

    public function mount()
    {
        //set default kelas
        $this->filterKelas = '';
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->filterTahunAkademik = TahunAkademik::select('id')->where('status', 'aktif')->first()->id;
        if (TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()) {
            $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;
        }
        //Ambil Mata pelajaran
        if (Auth::user()->role === 'guru') {
            //Ambil Jadwal Guru
            $jadwal = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->where('kelas_id', $this->filterKelas)->with(['mataPelajaran' => function ($query) {
                $query->select('id');
            }])->select('id', 'guru_id', 'mata_pelajaran_id')->get();

            //Ambil Id Mata Pelajaran dari setiap jadwal
            foreach ($jadwal as $d) {
                array_push($this->arrayMapel, $d->mataPelajaran->id);
            }
            $this->mapel = MataPelajaran::whereIn('id', $this->arrayMapel)->select('id', 'nama')->get();
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel->first()->id;
            } else {
                $this->filterMapel = '';
            }
        } else {
            $this->mapel = MataPelajaran::orderBy('nama', 'asc')->get();
            // dd($this->mapel);
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel->first()->id;
            } else {
                $this->filterMapel = '';
            }
        }
    }

    public function detail($id)
    {
        //ambil data
        $monitoring = MonitoringPembelajaranNew::find($id);
        $this->keterangan = $monitoring->keterangan;

        //ambil data kehadiran siswa yang sudah diinputkan
        $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
        foreach ($kehadiran as $k) {
            $this->presensi[$k->siswa_id] = $k->status;
        }
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function closeModal(){
        $this->resetPage();
    }

    public function export()
    {
        $namaKelas = Kelas::find($this->filterKelas)->nama;
        $namaMapel = MataPelajaran::find($this->filterMapel)->nama;
        $jml_siswa = Kelas::select('id')->find($this->filterKelas)->siswas->count();
        return Excel::download(new DaftarPertemuanExport($this->filterKelas, $this->filterMapel, $jml_siswa, $this->tanggalAwal, $this->tanggalAkhir), 'Daftar Pertemuan ' . $namaMapel . ' ' . $namaKelas . ' Tanggal ' . $this->tanggalAwal . ' sampai ' . $this->tanggalAkhir . '.xlsx');
    }

    public function updatedFilterKelas()
    {
        $this->arrayMapel = [];
        if (Auth::user()->role === 'guru') {
            //Ambil Jadwal Guru
            $jadwal = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->where('kelas_id', $this->filterKelas)->with(['mataPelajaran' => function ($query) {
                $query->select('id');
            }])->select('id', 'guru_id', 'mata_pelajaran_id')->get();

            //Ambil Id Mata Pelajaran dari setiap jadwal
            foreach ($jadwal as $d) {
                array_push($this->arrayMapel, $d->mataPelajaran->id);
            }
            $this->mapel = MataPelajaran::whereIn('id', $this->arrayMapel)->select('id', 'nama')->get();
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel->first()->id;
            } else {
                $this->filterMapel = '';
            }
        } else {
            $this->mapel = MataPelajaran::all();
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel->first()->id;
            } else {
                $this->filterMapel = '';
            }
        }
    }

    public function updatedFilterTahunAkademik()
    {
        $this->filterKelas = '';
        if (TahunAkademik::select('id')->find($this->filterTahunAkademik)->kelas->first()) {
            $this->filterKelas = TahunAkademik::select('id')->find($this->filterTahunAkademik)->kelas->first()->id;
        }
        $this->mapel = MataPelajaran::orderBy('nama',)->get();
        if (count($this->mapel) !== 0) {
            $this->filterMapel = $this->mapel->first()->id;
        } else {
            $this->filterMapel = '';
        }
    }



    public function render()
    {
        $monitoring = DB::table('monitoring_pembelajaran_news as a')
                    ->where('a.kelas_id',$this->filterKelas)
                    ->where('a.tanggal', '>=', $this->tanggalAwal)
                    ->where('a.tanggal', '<=', $this->tanggalAkhir)
                    ->where('a.mata_pelajaran_id',$this->filterMapel)
                    ->leftJoin('kehadiran_pembelajarans as b','b.monitoring_pembelajaran_id','a.monitoring_pembelajaran_id','left outer')
                    ->leftJoin('gurus as c', 'c.id','a.guru_id')
                    ->leftjoin('gurus as d','d.id','a.guru_piket_id')
                    ->select('a.monitoring_pembelajaran_id','a.tanggal','a.waktu_mulai','a.waktu_berakhir','a.topik','a.status_validasi','c.nama as guru','d.nama as piket', 
                    DB::raw("SUM(CASE WHEN b.status = 'hadir' THEN 1 ELSE 0 END) AS hadir"),
                    DB::raw("SUM(CASE WHEN b.status = 'izin' THEN 1 ELSE 0 END) AS izin"),
                    DB::raw("SUM(CASE WHEN b.status = 'sakit' THEN 1 ELSE 0 END) AS sakit"),
                    DB::raw("SUM(CASE WHEN b.status = 'alfa' THEN 1 ELSE 0 END) AS alfa"),
                    DB::raw("SUM(CASE WHEN b.status = 'dinas dalam' THEN 1 ELSE 0 END) AS dd"),
                    DB::raw("SUM(CASE WHEN b.status = 'dinas luar' THEN 1 ELSE 0 END) AS dl"),
                    DB::raw("COUNT(b.id) AS total")
                    )
                    ->groupBy('a.monitoring_pembelajaran_id')
                    ->orderBy('a.tanggal','asc')
                    ->distinct()
                    ->paginate(10);
        // dd($monitoring);

        $jml_siswa = 0;
        if (Kelas::where('id', $this->filterKelas)->first()) {
            $jml_siswa = count(Kelas::where('id', $this->filterKelas)->first()->siswas);
        }
        $siswa = [];
        if (Kelas::where('id', $this->filterKelas)->first()) {
            $siswa = Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10);
        }
        return view('livewire.daftar-pertemuan', [
            'kelas' => TahunAkademik::find($this->filterTahunAkademik)->kelas,
            'mapel' => $this->mapel,
            'pertemuan' => $monitoring,
            'jml_siswa' => $jml_siswa,
            'siswa' => $siswa,
            'tahunAkademik' => TahunAkademik::all()
        ]);
    }
    public function updatingMapel()
    {
        $this->resetPage();
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }
}
