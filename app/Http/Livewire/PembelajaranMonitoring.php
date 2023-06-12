<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Models\MonitoringPembelajaran;

class PembelajaranMonitoring extends Component
{

    public $jadwal;
    public $jadwalPengganti;
    public $day, $tanggal;
    public $tidakHadir = [];
    public function mount()
    {

        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');


        //Ambil Jadwal Hari ini
    }
    public function render()
    {
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'hari', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->with(
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
        }])->orderBy('waktu_mulai')->get()->sortBy(function ($query) {
            return $query->kelas->nama;
        });

        $this->tidakHadir = MonitoringPembelajaran::where('tanggal', $this->tanggal)->with(['jadwalPelajaran' => function ($query) {
            $query->with([
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]);
        }])->with(['kehadiranPembelajarans' => function ($query) {
            $query->where('status', '!=', 'hadir');
        }])->get();
        //Get Jadwal Pengganti
        $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->with(['jadwalPelajaran' => function ($query) {
            $query->with([
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
            ])->with(['monitoringPembelajarans' => function ($query) {
                $query->where('tanggal', $this->tanggal)->with(['kehadiranPembelajarans' => function ($query) {
                    $query->where('status', '!=', 'hadir');
                }]);
            }]);
        }])->get();
        return view('livewire.pembelajaran-monitoring');
    }
}
