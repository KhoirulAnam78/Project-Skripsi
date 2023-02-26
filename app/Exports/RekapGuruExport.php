<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapGuruExport implements FromView
{
    public $kelasAktif, $tanggalAwal, $tanggalAkhir;
    public function __construct($kelasAktif, $tanggalAwal, $tanggalAkhir)
    {
        $this->kelasAktif = $kelasAktif;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }
    public function view(): View
    {
        return view('livewire.tables.table-rekap-guru', [
            'guru' => Guru::select('id', 'nama', 'kode_guru')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->paginate()
        ]);
    }
}
