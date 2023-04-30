<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use App\Models\Angkatan;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\MonitoringKegiatan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;
use App\Models\MonitoringPembelajaran;

class DaftarKegiatanTanpanara extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $mapel;
    // public $filterKelas = '';
    public $kelas;
    public $kegiatan;
    public $filterAngkatan = '';
    public $presensi = [];
    public $jml_siswa;
    public $keterangan;

    public function mount($kegiatan)
    {
        $this->kegiatan = $kegiatan;
        $this->filterAngkatan = Angkatan::select('id')->first()->id;
        $this->jml_siswa = 0;
        $kelas = Kelas::where('angkatan_id', $this->filterAngkatan)->select('id')->get()->all();
        foreach ($kelas as $k) {
            $this->jml_siswa = $this->jml_siswa + $k->siswas->count();
        }
        // $this->kelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas;
        // $this->filterKelas = $this->kelas->first()->id;
    }

    // public function detail($id)
    // {
    //     //ambil data
    //     $monitoring = MonitoringPembelajaran::find($id);
    //     $this->keterangan = $monitoring->keterangan;

    //     //ambil data kehadiran siswa yang sudah diinputkan
    //     $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
    //     foreach ($kehadiran as $k) {
    //         $this->presensi[$k->siswa_id] = $k->status;
    //     }
    //     $this->dispatchBrowserEvent('show-detail-modal');
    // }

    // public function export()
    // {
    //     $namaKelas = Kelas::find($this->filterKelas)->nama;
    //     $namaMapel = MataPelajaran::find($this->filterTahunAkademik)->nama;
    //     $jml_siswa = Kelas::select('id')->find($this->filterKelas)->siswas->count();
    //     return Excel::download(new DaftarPertemuanExport($this->filterKelas, $this->filterTahunAkademik, $jml_siswa), 'Daftar Pertemuan ' . $namaMapel . ' ' . $namaKelas . '.xlsx');
    // }

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
        return view('livewire.daftar-kegiatan-tanpanara', [
            'angkatan' => Angkatan::latest()->get()->all(),
            'pertemuan' => MonitoringKegiatan::with('kehadiranKegiatan')->whereRelation('jadwalKegiatan', 'angkatan_id', $this->filterAngkatan)->whereRelation('jadwalKegiatan', 'kegiatan_id', $this->kegiatan->id)->paginate(10),

            'jml_siswa' => $this->jml_siswa,
            // 'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
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
