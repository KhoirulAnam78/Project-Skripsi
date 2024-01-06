<?php

namespace App\Exports;

use App\Models\Guru;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class RekapGuruExport implements FromView
{
    public $kelasAktif, $tanggalAwal, $tanggalAkhir,$search;
    public function __construct($kelasAktif, $tanggalAwal, $tanggalAkhir,$search)
    {
        $this->kelasAktif = $kelasAktif;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->search = $search;
    }
    public function view(): View
    {
        $data = [];
        if (Auth::user()->role === 'guru'){
            $guru = DB::table('gurus as a')
                    ->where('status','aktif')
                    ->where('user_id',Auth::user()->id)
                    ->select('a.id','a.kode_guru','a.nama')
                    ->paginate(1000);
        }else{
            $guru = DB::table('gurus as a')
                    ->where('status','aktif')
                    ->where('nama','like','%'.$this->search.'%')
                    ->select('a.id','a.kode_guru','a.nama')
                    ->paginate(1000);
        }

        foreach($guru as $g){
            $mapel = DB::table('jadwal_pelajarans as a')
            ->where('guru_id',$g->id)
            ->whereIn('a.kelas_id',$this->kelasAktif)
            ->join('mata_pelajarans as b','b.id','a.mata_pelajaran_id')
            ->select('b.nama as mapel','b.id')
            ->distinct()
            ->get();

            $mengajar = [];
            foreach($mapel as $m){
                //get jadwal pelajaran setiap mata pelajaran yang diajar
                $jadwal = DB::table('jadwal_pelajarans as a')
                        ->where('guru_id',$g->id)
                        ->whereIn('a.kelas_id',$this->kelasAktif)
                        ->where('mata_pelajaran_id',$m->id)
                        ->select('a.waktu_mulai','a.waktu_berakhir')
                        ->get();
        
                //get jumlah jam wajib selama seminggu
                $diff = 0;
                foreach ($jadwal as $j) {
                    $datetime1 = strtotime($j->waktu_mulai);
                    $datetime2 = strtotime($j->waktu_berakhir);
                    $interval = abs($datetime2 - $datetime1);
                    $minutes = round($interval / 60);
                        // dd($minutes);
                    $perbedaan = floor($minutes / 35);
                    $diff = $diff + $perbedaan;
                }
                
                //get monitoring tidak terlaksana
                $monitoring = DB::table('monitoring_pembelajaran_news')
                            ->where('guru_id',$g->id)
                            ->whereIn('kelas_id',$this->kelasAktif)
                            ->where('mata_pelajaran_id',$m->id)
                            ->where('tanggal', '>=', $this->tanggalAwal)
                            ->where('tanggal', '<=', $this->tanggalAkhir)
                            ->select('tanggal','waktu_mulai','waktu_berakhir','keterangan','status_validasi')
                            ->get();
                $keterangan = [];
                $tidak_terlaksana = 0;
                foreach($monitoring as $mo){
                    $keterangan =[];
                    if ($mo->status_validasi === 'tidak terlaksana') {
                        $datetime1 = strtotime($mo->waktu_mulai);
                        $datetime2 = strtotime($mo->waktu_berakhir);
                        $interval = abs($datetime2 - $datetime1);
                        $minutes = round($interval / 60);
                        $perbedaan = floor($minutes / 35);
                        $tidak_terlaksana = $tidak_terlaksana + $perbedaan;
                        array_push($keterangan,[$mo->tanggal.' : '.$mo->keterangan]);
                    }
                }
                
                array_push($mengajar,['mapel' => $m->mapel,'jam'=>$diff,'tidak_terlaksana'=>$tidak_terlaksana,'keterangan'=>$keterangan]);
            }

            array_push($data,['nama' => $g->nama,'kode_guru'=>$g->kode_guru,'mengajar'=>$mengajar]);
        }
        return view('livewire.tables.table-rekap-guru', [
            'data' => $data,
            'guru' => $guru
        ]);
    }
}
