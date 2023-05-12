<?php

namespace App\Http\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class JadwalSiswa extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filterHari;
    public $filterKegiatan, $tahunAkademik, $angkatan, $kelas;
    public function mount()
    {
        $this->tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
        $this->filterKegiatan = 'akademik';
        $data = Siswa::where('user_id', Auth::user()->id)->select('id')->with(['kelas' => function ($query) {
            $query->whereRelation('tahunAkademik', 'status', 'aktif')->select('kelas_id', 'angkatan_id');
        }])->first();
        $this->angkatan = $data->kelas->first()->angkatan_id;
        $this->kelas = $data->kelas->first()->kelas_id;
    }
    public function render()
    {
        if ($this->filterKegiatan === 'akademik') {
            // $jadwal = Siswa::where('user_id', Auth::user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
            //     $query->whereRelation('tahunAkademik', 'status', 'aktif')->with('jadwalPelajarans');
            // }])->first();
            $jadwal = JadwalPelajaran::where('kelas_id', $this->kelas)->where('hari', 'like', '%' . $this->filterHari . '%')->with('mataPelajaran', 'guru')->orderBy('hari', 'asc')->orderBy('waktu_mulai', 'asc')->paginate(10);
        } else {
            $jadwal = JadwalKegiatan::where('angkatan_id', $this->angkatan)->where('tahun_akademik_id', $this->tahunAkademik)->with('kegiatan')->orderBy('hari', 'asc')->orderBy('waktu_mulai', 'asc')->paginate(10);
        }
        // $jadwalKegiatan = JadwalKegiatan::where('angkatan_id',$angkatan_id)
        return view('livewire.jadwal-siswa', [
            'jadwal' => $jadwal,
            // 'jadwalKegiatan' => 
        ]);
    }

    public function updatingFilterHari()
    {
        $this->resetPage();
    }
}
