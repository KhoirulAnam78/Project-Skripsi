<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class MonitoringPage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filterKegiatan;

    public function mount()
    {
        $this->filterKegiatan = 'pembelajaran';
    }

    public function updatedFilterKegiatan()
    {

        $this->resetPage();
    }

    // public function updatedFilterKelas()
    // {

    //     //Ambil Jadwal Hari ini
    //     $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
    //         [
    //             'kelas' => function ($query) {
    //                 $query->select('id', 'nama');
    //             },
    //             'mataPelajaran' => function ($query) {
    //                 $query->select('id', 'nama');
    //             },
    //             'guru' => function ($query) {
    //                 $query->select('id', 'nama');
    //             },
    //         ]
    //     )->with(['monitoringPembelajarans' => function ($query) {
    //         $query->where('tanggal', $this->tanggal)->get();
    //     }])->get();

    //     //Get Jadwal Pengganti
    //     $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
    // }

    public function render()
    {
        return view('livewire.monitoring-page');
    }
}
