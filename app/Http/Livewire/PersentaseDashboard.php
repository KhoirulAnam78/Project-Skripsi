<?php

namespace App\Http\Livewire;

use DateTime;
use DatePeriod;
use DateInterval;
use DateTimeZone;
use Livewire\Component;
use App\Models\Kegiatan;
use Carbon\CarbonPeriod;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\KehadiranKegnas;
use App\Models\MonitoringKegnas;
use App\Models\KehadiranKegiatan;
use App\Models\MonitoringKegiatan;
use Illuminate\Support\Facades\DB;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;
use App\Models\MonitoringPembelajaranNew;

class PersentaseDashboard extends Component
{
    public $bulan = [];
    public $filterBulan;
    // public $pembelajaran;
    public $presensi = [];
    public $grafikJmlPembelajaran = [];
    public $grafikJmlTidakHadir = [];
    public $jumlahKegiatan = [], $tgl;
    public $presensiKegiatan = [];
    public function mount()
    {
        setlocale(LC_ALL, 'IND');

        //Ambil data untuk filter bulan di sahboard
        $akademikAktif = TahunAkademik::where('status', 'aktif')->first();
        $start    = new DateTime($akademikAktif->tgl_mulai);
        // dd(date('d-m-Y', strtotime("Last Friday of 2023-02-01")));
        // dd($start->modify('first monday')->format('d-m-Y'));
        $end      = new DateTime($akademikAktif->tgl_berakhir);

        // $interval = DateInterval::createFromDateString('1 month');
        $interval = new DateInterval('P1M');
        $period   = new DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $dt->format('d-m-Y'));
            $tanggal = $dt->format('d-m-Y');
            // $bulan = strftime('%B %Y', strtotime($dt->format('d-m-Y')));
            $bulan = $carbon->translatedFormat('F Y');
            $tgl = $dt->format('d');
            array_push($this->bulan, ['bulan' => $bulan, 'tanggal' => $tanggal,]);
        }

        //Ambil Bulan Tahun dan jumlah Pembelajaran tidak terlaksana
        foreach ($this->bulan as $b) {
            $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $b['tanggal']);


            // GRAFIK PEMBELAJARAN TIDAK TERLAKSANA
            $jml =DB::table('monitoring_pembelajaran_news as a')
            ->whereMonth('a.tanggal', $carbon->translatedFormat('m'))->whereYear('a.tanggal', '=', $carbon->translatedFormat('Y'))
            ->where('status_validasi','tidak terlaksana')
            ->count();

            array_push($this->grafikJmlPembelajaran, ['bulan' => $carbon->translatedFormat('F Y'), 'jml' => $jml]);


            // GRAFIK JUMLAH TIDAK HADIR

            //ambil monitoring
            $monitoring = DB::table('monitoring_pembelajaran_news as a')
            ->join('kehadiran_pembelajarans as b','b.monitoring_pembelajaran_id', 'a.monitoring_pembelajaran_id')
            ->whereMonth('a.tanggal', $carbon->translatedFormat('m'))->whereYear('a.tanggal', '=', $carbon->translatedFormat('Y'))
            ->select(
                DB::raw("SUM(CASE WHEN b.status != 'hadir' THEN 1 ELSE 0 END) as jml_tidak_hadir")
            )
            ->first();
                
            $tidakHadir = 0;
            if($monitoring){
                $tidakHadir = (int) $monitoring->jml_tidak_hadir;
            }
            array_push($this->grafikJmlTidakHadir, $tidakHadir);
        }
        

        $this->filterBulan = $tgl . "-" . \Carbon\Carbon::now()->translatedFormat('m-Y');

        //Ambil Jumlah Pembelajaran Terlaksana dan tidak terlaksana

        $this->tgl = new DateTime($this->filterBulan);

        $presensi = DB::table('monitoring_pembelajaran_news as a')
        ->join('kehadiran_pembelajarans as b', 'b.monitoring_pembelajaran_id','a.monitoring_pembelajaran_id')
        ->whereMonth('a.tanggal', $this->tgl->format('m'))
        ->whereYear('a.tanggal', '=', $this->tgl->format('Y'))
        ->select(
            DB::raw("SUM(CASE WHEN b.status = 'hadir' THEN 1 ELSE 0 END) as jml_hadir"),
            DB::raw("SUM(CASE WHEN b.status = 'sakit' THEN 1 ELSE 0 END) as jml_sakit"),
            DB::raw("SUM(CASE WHEN b.status = 'alfa' THEN 1 ELSE 0 END) as jml_alfa"),
            DB::raw("SUM(CASE WHEN b.status = 'izin' THEN 1 ELSE 0 END) as jml_izin"),
            DB::raw("SUM(CASE WHEN b.status = 'dinas dalam' THEN 1 ELSE 0 END) as jml_dd"),
            DB::raw("SUM(CASE WHEN b.status = 'dinas luar' THEN 1 ELSE 0 END) as jml_dl")
        )
        ->first();

        array_push($this->presensi, ['hadir' => $presensi->jml_hadir, 'izin' => $presensi->jml_izin, 'sakit' => $presensi->jml_sakit, 'alfa' => $presensi->jml_alfa, 'dd' => $presensi->jml_dd, 'dl' => $presensi->jml_dl]);
    }

    public function updatedFilterBulan()
    {
        $this->presensi = [];
        $this->tgl = new DateTime($this->filterBulan);
        
        $presensi = DB::table('monitoring_pembelajaran_news as a')
        ->join('kehadiran_pembelajarans as b', 'b.monitoring_pembelajaran_id','a.monitoring_pembelajaran_id')
        ->whereMonth('a.tanggal', $this->tgl->format('m'))
        ->whereYear('a.tanggal', '=', $this->tgl->format('Y'))
        ->select(
            DB::raw("SUM(CASE WHEN b.status = 'hadir' THEN 1 ELSE 0 END) as jml_hadir"),
            DB::raw("SUM(CASE WHEN b.status = 'sakit' THEN 1 ELSE 0 END) as jml_sakit"),
            DB::raw("SUM(CASE WHEN b.status = 'alfa' THEN 1 ELSE 0 END) as jml_alfa"),
            DB::raw("SUM(CASE WHEN b.status = 'izin' THEN 1 ELSE 0 END) as jml_izin"),
            DB::raw("SUM(CASE WHEN b.status = 'dinas dalam' THEN 1 ELSE 0 END) as jml_dd"),
            DB::raw("SUM(CASE WHEN b.status = 'dinas luar' THEN 1 ELSE 0 END) as jml_dl")
        )
        ->first();

        array_push($this->presensi, ['hadir' => $presensi->jml_hadir, 'izin' => $presensi->jml_izin, 'sakit' => $presensi->jml_sakit, 'alfa' => $presensi->jml_alfa, 'dd' => $presensi->jml_dd, 'dl' => $presensi->jml_dl]);

        $this->dispatchBrowserEvent('refresh-chart');
    }
    public function render()
    {
        $pembelajaran = DB::table('monitoring_pembelajaran_news as a')
        ->whereMonth('a.tanggal', $this->tgl->format('m'))
        ->whereYear('a.tanggal', '=', $this->tgl->format('Y'))
        ->select(
            DB::raw("SUM(CASE WHEN a.status_validasi = 'terlaksana' THEN 1 ELSE 0 END) as terlaksana"),
            DB::raw("SUM(CASE WHEN a.status_validasi != 'terlaksana' THEN 1 ELSE 0 END) as tidak_terlaksana"),
            DB::raw("COUNT(*) as total")
        )->first();
        return view('livewire.persentase-dashboard',compact('pembelajaran'));
    }
}