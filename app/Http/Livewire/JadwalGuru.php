<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class JadwalGuru extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filterHari;
    public $kelasAktif = [];

    public function mount()
    {
        $this->filterHari = '';
        $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
        foreach ($data as $d) {
            array_push($this->kelasAktif, $d->id);
        }
    }

    public function render()
    {
        // $jadwal = Guru::where('user_id', Auth::user()->id)->select('id', 'user_id')->with(['jadwalPelajarans' => function ($query) {
        //     $query->with(['kelas' => function ($query) {
        //         $query->whereRelation('tahunAkademik', 'status', 'aktif');
        //     }]);
        // }])->get();

        $jadwal = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->whereIn('kelas_id', $this->kelasAktif)->where('hari', 'like', '%' . $this->filterHari . '%')->with(['kelas' => function ($query) {
            $query->select('id', 'nama');
        }])->with(['mataPelajaran' => function ($query) {
            $query->select('id', 'nama');
        }])->orderBy('hari', 'asc')->paginate(10);

        return view('livewire.jadwal-guru', [
            'jadwal' => $jadwal
        ]);
    }
}
