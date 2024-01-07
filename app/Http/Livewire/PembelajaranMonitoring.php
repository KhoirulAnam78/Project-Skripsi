<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use Illuminate\Support\Facades\DB;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;

class PembelajaranMonitoring extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $jadwalPengganti;
    public $day, $tanggal;
    public $tidakHadir = [];
    public $filterTampilan;
    public $time;
    public function mount()
    {
        $this->filterTampilan = 'semua';
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        
        $this->time =  \Carbon\Carbon::now()->translatedFormat('H:i');

        //Ambil Jadwal Hari ini
    }
    public function render()
    {
        $this->tidakHadir = DB::table('kehadiran_pembelajarans as a')
        ->join('monitoring_pembelajaran_news as b', 'b.monitoring_pembelajaran_id', '=', 'a.monitoring_pembelajaran_id')
        ->join('kelas as c', 'c.id', '=', 'b.kelas_id')
        ->join('mata_pelajarans as d', 'd.id', '=', 'b.mata_pelajaran_id')
        ->join('siswas as e', 'e.id', '=', 'a.siswa_id')
        ->where('b.tanggal', '=', $this->tanggal)
        ->where('a.status', '!=', 'hadir')
        ->when($this->filterTampilan != 'semua', function($q) {
            $q->where('b.waktu_mulai', '<', $this->time)->where('b.waktu_berakhir', '>', $this->time);
        })
        ->groupBy('c.nama', 'd.nama') // Group by class and subject
        ->select(
            'c.nama as kelas',
            'd.nama as mata_pelajaran',
            DB::raw('GROUP_CONCAT(e.nama ," (",a.status,")") as nama_siswa') // Concatenate names of non-attending students
        )
        ->get();
        return view('livewire.pembelajaran-monitoring', [
            'jadwal' => DB::table('jadwal_pelajarans as a')
            ->where('a.hari', 'senin')
            ->leftJoin('kelas as b', 'b.id', '=', 'a.kelas_id')
            ->leftJoin('gurus as c', 'c.id', '=', 'a.guru_id')
            ->leftJoin('mata_pelajarans as d', 'd.id', '=', 'a.mata_pelajaran_id')
            ->leftJoin('monitoring_pembelajaran_news as e', function ($join) {
                $join->on('e.mata_pelajaran_id', '=', 'a.mata_pelajaran_id')
                    ->on('e.kelas_id', '=', 'a.kelas_id')
                    ->on('e.guru_id', '=', 'a.guru_id')
                    ->where('e.tanggal', '=', $this->tanggal);
            })
            ->when($this->filterTampilan != 'semua', function($q){
                $q->where('a.hari', $this->day)->where('a.waktu_mulai', '<', $this->time)->where('a.waktu_berakhir', '>', $this->time);
            })
            ->leftJoin('kehadiran_pembelajarans as f', 'f.monitoring_pembelajaran_id', '=', 'e.monitoring_pembelajaran_id')
            ->select(
                'a.id', 'a.hari', 'a.waktu_mulai', 'a.waktu_berakhir', 'a.kelas_id', 'a.mata_pelajaran_id', 'a.guru_id',
                'b.nama as kelas', 'c.nama as guru', 'd.nama as mata_pelajaran', 'e.topik', 'e.status_validasi',
                'e.monitoring_pembelajaran_id',
                DB::raw("COALESCE(SUM(CASE WHEN f.status = 'hadir' THEN 1 ELSE 0 END), 0) as jml_hadir"),
                DB::raw("COALESCE(SUM(CASE WHEN f.status = 'sakit' THEN 1 ELSE 0 END), 0) as jml_sakit"),
                DB::raw("COALESCE(SUM(CASE WHEN f.status = 'alfa' THEN 1 ELSE 0 END), 0) as jml_alfa"),
                DB::raw("COALESCE(SUM(CASE WHEN f.status = 'izin' THEN 1 ELSE 0 END), 0) as jml_izin"),
                DB::raw("COALESCE(SUM(CASE WHEN f.status = 'dinas dalam' THEN 1 ELSE 0 END), 0) as jml_dd"),
                DB::raw("COALESCE(SUM(CASE WHEN f.status = 'dinas luar' THEN 1 ELSE 0 END), 0) as jml_dl")
            )
            ->groupBy('a.id', 'a.hari', 'a.waktu_mulai', 'a.waktu_berakhir', 'a.kelas_id', 'a.mata_pelajaran_id', 'a.guru_id',
                'b.nama', 'c.nama', 'd.nama', 'e.topik', 'e.status_validasi')
            ->paginate(12)
        ]);

        // if ($this->filterTampilan == 'semua') {
            
        // } else {
        //     return view('livewire.pembelajaran-monitoring', [
        //         'jadwal' => JadwalPelajaran::select('id', 'waktu_mulai', 'hari', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->where('waktu_mulai', '<', $this->time)->where('waktu_berakhir', '>', $this->time)->with(
        //             [
        //                 'kelas' => function ($query) {
        //                     $query->select('id', 'nama');
        //                 },
        //                 'mataPelajaran' => function ($query) {
        //                     $query->select('id', 'nama');
        //                 },
        //                 'guru' => function ($query) {
        //                     $query->select('id', 'nama');
        //                 },
        //             ]
        //         )->with(['monitoringPembelajarans' => function ($query) {
        //             $query->where('tanggal', $this->tanggal)->get();
        //         }])->orderBy('waktu_mulai')->paginate(12)
        //     ]);
        // }
    }
}