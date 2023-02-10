<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MonitoringPembelajaran;

class DaftarPertemuan extends Component
{
    public $mapel;
    public $filterKelas = '';
    public $filterMapel = null;
    public function mount()
    {
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
        if (JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()) {
            $this->filterMapel = JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()->id;
        } else {
            $this->filterMapel = null;
        }
    }

    public function updatedFilterKelas()
    {
        if (JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()) {
            $this->filterMapel = JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()->id;
        } else {
            $this->filterMapel = null;
        }
    }

    public function render()
    {
        $this->mapel = JadwalPelajaran::where('kelas_id', $this->filterKelas)->get()->all();
        // dd(MonitoringPembelajaran::with('kehadiranPembelajarans')->where('jadwal_pelajaran_id', $this->filterMapel)->paginate(10));
        return view('livewire.daftar-pertemuan', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
            'pertemuan' => MonitoringPembelajaran::with('kehadiranPembelajarans')->where('jadwal_pelajaran_id', $this->filterMapel)->paginate(10),
            'jml_siswa' => count(Kelas::where('id', $this->filterKelas)->first()->siswas)
        ]);
    }
}
