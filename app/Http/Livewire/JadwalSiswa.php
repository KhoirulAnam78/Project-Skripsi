<?php

namespace App\Http\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class JadwalSiswa extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filterHari;
    public $filterKegiatan;
    public function mount()
    {
        $this->filterHari = 'Senin';
        $this->filterKegiatan = 'Pembelajaran';
    }
    public function render()
    {
        $jadwal = Siswa::where('user_id', Auth::user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
            $query->whereRelation('tahunAkademik', 'status', 'aktif')->with('jadwalPelajarans');
        }])->first();
        return view('livewire.jadwal-siswa', [
            'jadwalPelajaran' => $jadwal->kelas->first()->jadwalPelajarans()->where('hari', $this->filterHari)->with('mataPelajaran', 'guru')->paginate(10)
        ]);
    }

    public function updatingFilterHari()
    {
        $this->resetPage();
    }
}
