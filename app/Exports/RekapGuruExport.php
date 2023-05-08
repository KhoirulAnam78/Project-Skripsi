<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::user()->role === 'guru') {
            $data = Guru::select('id', 'nama', 'kode_guru')->where('id', Auth::user()->guru->id)->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir', 'keterangan')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->orderBy('nama', 'asc')->paginate();
        } else {
            $data = Guru::select('id', 'nama', 'kode_guru')->with(['jadwalPelajarans' => function ($query) {
                $query->select('id', 'waktu_mulai', 'waktu_berakhir', 'guru_id', 'mata_pelajaran_id')->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
                    $query->select('id', 'status_validasi', 'jadwal_pelajaran_id', 'waktu_mulai', 'waktu_berakhir', 'keterangan')->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir);
                }])->with(['mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                }]);
            }])->orderBy('nama', 'asc')->paginate();
        }
        return view('livewire.tables.table-rekap-guru', [
            'guru' => $data
        ]);
    }
}
