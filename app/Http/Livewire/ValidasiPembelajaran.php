<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;

class ValidasiPembelajaran extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //filter
    public $filterKelas = '';
    public $jadwal, $day, $tanggal, $jadwalPengganti;
    //menampung data siswa berdasarkan kelas
    public $student;
    //atribut inputan
    public $waktu_mulai, $waktu_berakhir, $topik;
    //menampung kehadiran siswa
    public $presensi = [];
    public $update = false;

    public function mount()
    {
        //mengambil nama hari 
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;

        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');

        //Mengambil jadwal hari ini
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
            [
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]
        )->with(['monitoringPembelajarans' => function ($query) {
            $query->where('tanggal', $this->tanggal)->get();
        }])->get();

        //Get Jadwal Pengganti
        $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
    }

    public function updatedFilterKelas()
    {
        //Mengambil jadwal hari ini
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
            [
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]
        )->with(['monitoringPembelajarans' => function ($query) {
            $query->where('tanggal', $this->tanggal)->get();
        }])->get();

        //Get Jadwal Pengganti
        $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
    }

    public function showId($id)
    {
        //ambil data jadwal
        $data = JadwalPelajaran::find($id);

        //mengambil semua data siswa berdasarkan kelas default
        $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas;

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            // $this->tanggal = $monitoring->tanggal;
            $this->topik = $monitoring->topik;

            //ambil data kehadiran siswa yang sudah diinputkan
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
        } else {
            $this->topik = '';
        }

        $this->dispatchBrowserEvent('show-modal');
    }

    public function presensi($id)
    {
        //mengambil semua data siswa berdasarkan kelas default
        $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas;

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            // $this->tanggal = $monitoring->tanggal;
            $this->topik = $monitoring->topik;

            //ambil data kehadiran siswa yang sudah diinputkan
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
            $this->update = true;
        } else {
            $this->topik = '';
            $this->update = false;
        }

        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function render()
    {
        return view('livewire.validasi-pembelajaran', [
            'jadwal' => $this->jadwal,
            'jadwalPengganti' => $this->jadwalPengganti,
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
        ]);
    }
}
