<?php

namespace App\Http\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use App\Models\TahunAkademik;

class RekapSiswa extends Component
{
    public $filterKelas;
    public $tanggalAwal, $tanggalAkhir;
    public $search = '';
    public $siswa;

    public function mount()
    {
        //ambil kelas
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
    }
    public function render()
    {
        return view('livewire.rekap-siswa', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'dataSiswa' => Siswa::whereRelation('kelas', 'kelas_id', $this->filterKelas)->with(['kehadiranPembelajarans' => function ($query) {
                $query->whereRelation('monitoringPembelajaran', 'tanggal', '>=', $this->tanggalAwal)->whereRelation('monitoringPembelajaran', 'tanggal', '<=', $this->tanggalAkhir);
            }])->where('nama', 'like', '%' . $this->search . '%')->paginate(10)
        ]);
    }
}
