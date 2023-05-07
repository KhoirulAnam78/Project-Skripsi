<?php

namespace App\Http\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class JadwalSiswa extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filterHari;
    public $filterKegiatan;
    public function mount()
    {
        $this->filterKegiatan = 'akademik';
    }
    public function render()
    {
        $jadwal = Siswa::where('user_id', Auth::user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
            $query->whereRelation('tahunAkademik', 'status', 'aktif')->with('jadwalPelajarans');
        }])->first();
        $kelas_id =  $jadwal->kelas->first()->id;
        $angkatan_id = $jadwal->kelas->first()->angkatan_id;
        // $jadwalKegiatan = JadwalKegiatan::where('angkatan_id',$angkatan_id)
        return view('livewire.jadwal-siswa', [
            'jadwalPelajaran' => JadwalPelajaran::where('kelas_id', $kelas_id)->where('hari', 'like', '%' . $this->filterHari . '%')->with('mataPelajaran', 'guru')->orderBy('hari', 'asc')->orderBy('waktu_mulai', 'asc')->paginate(10),
            // 'jadwalKegiatan' => 
        ]);
    }

    public function updatingFilterHari()
    {
        $this->resetPage();
    }
}
