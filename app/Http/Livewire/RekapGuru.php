<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use Livewire\Component;
use App\Models\TahunAkademik;
use App\Exports\RekapGuruExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapGuru extends Component
{
    public $tanggalAwal;
    public $tanggalAkhir;
    public $kelasAktif = [];
    public $search = '';

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
        return Excel::download(new RekapGuruExport($this->tanggalAwal, $this->tanggalAkhir, $this->kelasAktif), 'Rekap Guru ' . 'Tanggal ' . $this->tanggalAwal . ' Sampai ' . $this->tanggalAkhir . '.xlsx');
    }
    public function render()
    {
        return view('livewire.rekap-guru', [
            'guru' => Guru::select('id', 'nama', 'kode_guru')->where('nama', 'like', '%' . $this->search . '%')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->paginate(10)
        ]);
    }
}
