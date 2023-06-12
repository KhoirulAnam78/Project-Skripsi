<?php

namespace App\Http\Livewire;

use App\Models\JadwalKegiatan;
use App\Models\MonitoringKegiatan;
use App\Models\MonitoringKegnas;
use Livewire\Component;

class KegiatanMonitoring extends Component
{
    public $jadwal;
    public $day, $tanggal;
    public $tidakHadirKegiatan = [], $tidakHadirKegnas = [];

    public function mount()
    {
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
    }
    public function render()
    {
        $this->jadwal = JadwalKegiatan::where('hari', 'Setiap Hari')->orwhere('hari', $this->day)->with('kegiatan')->with('angkatan')->with(['monitoringKegiatan' => function ($query) {
            $query->where('tanggal', $this->tanggal)->with('kehadiranKegiatan');
        }])->with(['monitoringKegnas' => function ($query) {
            $query->where('tanggal', $this->tanggal)->with(['narasumber' => function ($query) {
                $query->select('id', 'nama');
            }])->with('kehadiranKegnas');
        }])->orderBy('waktu_mulai')->get();

        $this->tidakHadirKegiatan = MonitoringKegiatan::where('tanggal', $this->tanggal)->with(['jadwalKegiatan' => function ($query) {
            $query->with([
                'kegiatan' => function ($query) {
                    $query->select('id', 'nama');
                },
                'angkatan' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]);
        }])->with(['kehadiranKegiatan' => function ($query) {
            $query->where('status', '!=', 'hadir');
        }])->get();

        $this->tidakHadirKegnas = MonitoringKegnas::where('tanggal', $this->tanggal)->with(['jadwalKegiatan' => function ($query) {
            $query->with([
                'kegiatan' => function ($query) {
                    $query->select('id', 'nama');
                },
                'angkatan' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]);
        }])->with(['kehadiranKegnas' => function ($query) {
            $query->where('status', '!=', 'hadir');
        }])->get();

        return view('livewire.kegiatan-monitoring');
    }
}
