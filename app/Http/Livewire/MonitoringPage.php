<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;

class MonitoringPage extends Component
{
    public $filterKegiatan;
    public $filterKelas;
    public $kelas;
    public $jadwal;
    public $jadwalPengganti;
    public $day, $tanggal;

    public function mount()
    {
        $this->filterKegiatan = 'pembelajaran';
        $this->kelas = TahunAkademik::where('status', 'aktif')->select('id', 'status')->first()->kelas;
        $this->filterKelas = $this->kelas->first()->id;
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');


        //Ambil Jadwal Hari ini
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
            [
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
                'guru' => function ($query) {
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

        //Ambil Jadwal Hari ini
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
            [
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
                'guru' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]
        )->with(['monitoringPembelajarans' => function ($query) {
            $query->where('tanggal', $this->tanggal)->get();
        }])->get();

        //Get Jadwal Pengganti
        $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
    }
    public function render()
    {
        return view('livewire.monitoring-page');
    }
}
