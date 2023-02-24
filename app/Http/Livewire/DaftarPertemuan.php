<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\MonitoringPembelajaran;

class DaftarPertemuan extends Component
{
    public $mapel;
    public $filterKelas = '';
    public $filterMapel = null;
    public function mount()
    {
        //set default kelas
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
        //Ambil Mata pelajaran
        $this->filterMapel = MataPelajaran::first()->id;
        // if (JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()) {
        //     $this->filterMapel = JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()->id;
        // } else {
        //     $this->filterMapel = null;
        // }
    }

    public function render()
    {
        $this->mapel = MataPelajaran::all();
        return view('livewire.daftar-pertemuan', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
            'pertemuan' => MonitoringPembelajaran::with('kehadiranPembelajarans')->whereRelation('jadwalPelajaran', 'mata_pelajaran_id', $this->filterMapel)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->paginate(10),
            'jml_siswa' => count(Kelas::where('id', $this->filterKelas)->first()->siswas)
        ]);
    }
}
