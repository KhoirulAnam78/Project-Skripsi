<?php

namespace App\Http\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MonitoringPembelajaran;
use App\Exports\RekapPembelajaranSiswa;

class PembelajaranSiswa extends Component
{
    public $tanggalAwal, $tanggalAkhir;
    public $jadwalId = [];

    public function mount()
    {
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $jadwal = Siswa::where('user_id', Auth::user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
            $query->whereRelation('tahunAkademik', 'status', 'aktif');
        }])->first();
        foreach ($jadwal->kelas->first()->jadwalPelajarans as $value) {
            array_push($this->jadwalId, $value->id);
        }
    }

    public function export()
    {
        return Excel::download(new RekapPembelajaranSiswa($this->jadwalId, $this->tanggalAwal, $this->tanggalAkhir, Auth::user()->siswa->id), 'Rekapitulasi pembelajaran siswa ' . $this->tanggalAwal . ' sampai ' . $this->tanggalAkhir . '.xlsx');
    }

    public function render()
    {
        return view('livewire.pembelajaran-siswa', [
            'monitoring' => MonitoringPembelajaran::whereIn('jadwal_pelajaran_id', $this->jadwalId)->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->select('id', 'topik', 'jadwal_pelajaran_id', 'tanggal', 'waktu_mulai', 'waktu_berakhir')->with(['kehadiranPembelajarans' => function ($query) {
                $siswaID = Siswa::where('user_id', Auth::user()->id)->first()->id;
                $query->where('siswa_id', $siswaID)->select('status', 'monitoring_pembelajaran_id', 'siswa_id')->get();
            }])->paginate(10)
        ]);
    }
}
