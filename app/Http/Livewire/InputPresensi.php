<?php

namespace App\Http\Livewire;

use App\Models\JadwalPelajaran;
use App\Models\TahunAkademik;
use Livewire\Component;

class InputPresensi extends Component
{
    public $filterKelas = '';
    public $day;
    public $mapel;

    public function mount()
    {
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
    }

    public function updatedFilterKelas()
    {
        $this->mapel = JadwalPelajaran::with('guru')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
    }
    public function render()
    {
        return view('livewire.input-presensi', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel
        ]);
    }
}
