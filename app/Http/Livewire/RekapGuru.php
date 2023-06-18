<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Exports\RekapGuruExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapGuru extends Component
{
    public $tanggalAwal;
    public $tanggalAkhir;
    public $kelasAktif = [];
    public $search = '';

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
        foreach ($data as $d) {
            array_push($this->kelasAktif, $d->id);
        }
    }
    public function export()
    {
        return Excel::download(new RekapGuruExport($this->kelasAktif, $this->tanggalAwal, $this->tanggalAkhir), 'Rekap Guru ' . 'Tanggal ' . $this->tanggalAwal . ' Sampai ' . $this->tanggalAkhir . '.xlsx');
    }
    public function render()
    {
        if (Auth::user()->role === 'guru') {
            $data = Guru::select('id', 'nama', 'kode_guru')->where('id', Auth::user()->guru->id)->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'tanggal', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir', 'keterangan')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->orderBy('nama', 'asc')->paginate(10);
        } else {
            $data = Guru::select('id', 'nama', 'kode_guru')->where('nama', 'like', '%' . $this->search . '%')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'tanggal', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir', 'keterangan')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->orderBy('nama', 'asc')->paginate(10);
        }
        // dd($data);
        return view('livewire.rekap-guru', [
            'guru' => $data
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
