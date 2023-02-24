<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use Livewire\Component;
use App\Models\TahunAkademik;

class RekapGuru extends Component
{
    public $filterRekap;
    public $filterBulan;
    public $filterMinggu;
    public $kelasAktif = [];

    public function mount()
    {
        $this->filterRekap = 'minggu';
        $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
        foreach ($data as $d) {
            array_push($this->kelasAktif, $d->id);
        }
    }
    public function render()
    {
        return view('livewire.rekap-guru', [
            'guru' => Guru::select('id', 'nama', 'kode_guru')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir');
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->paginate(10)
        ]);
    }
}
