<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;
use App\Models\MonitoringPembelajaran;

class DaftarPertemuan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $mapel;
    public $filterKelas = '';
    public $filterMapel = null;
    public $presensi = [];
    public $keterangan;
    public $arrayMapel = [];

    public function mount()
    {
        //set default kelas
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
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
            $this->mapel = MataPelajaran::all();
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
        $monitoring = MonitoringPembelajaran::find($id);
        $this->keterangan = $monitoring->keterangan;

        //ambil data kehadiran siswa yang sudah diinputkan
        $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
        foreach ($kehadiran as $k) {
            $this->presensi[$k->siswa_id] = $k->status;
        }
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function export()
    {
        $namaKelas = Kelas::find($this->filterKelas)->nama;
        $namaMapel = MataPelajaran::find($this->filterMapel)->nama;
        $jml_siswa = Kelas::select('id')->find($this->filterKelas)->siswas->count();
        return Excel::download(new DaftarPertemuanExport($this->filterKelas, $this->filterMapel, $jml_siswa), 'Daftar Pertemuan ' . $namaMapel . ' ' . $namaKelas . '.xlsx');
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

    public function render()
    {
        return view('livewire.daftar-pertemuan', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
            'pertemuan' => MonitoringPembelajaran::with('kehadiranPembelajarans')->whereRelation('jadwalPelajaran', 'mata_pelajaran_id', $this->filterMapel)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->paginate(10),
            'jml_siswa' => count(Kelas::where('id', $this->filterKelas)->first()->siswas),
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
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
