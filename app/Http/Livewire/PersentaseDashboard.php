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
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;

class PersentaseDashboard extends Component
{
    public $bulan = [];
    public $filterBulan;
    public $pembelajaran;
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
        // dd($period);

        foreach ($period as $dt) {
            $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $dt->format('d-m-Y'));
            // $bulan = strftime('%B %Y', strtotime($dt->format('d-m-Y')));
            $bulan = $carbon->translatedFormat('F Y');
            $tanggal = $dt->format('d-m-Y');

            array_push($this->bulan, ['bulan' => $bulan, 'tanggal' => $tanggal,]);
        }
        // $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $end->format('d-m-Y'));
        // // $bulan = strftime('%B %Y', strtotime($dt->format('d-m-Y')));
        // $bulan = $carbon->translatedFormat('F Y');
        // $tanggal = $dt->format('d-m-Y');

        // array_push($this->bulan, ['bulan' => $bulan, 'tanggal' => $tanggal,]);

        //Ambil Bulan Tahun dan jumlah Pembelajaran tidak terlaksana
        foreach ($this->bulan as $b) {
            $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $b['tanggal']);
            $monitoring = MonitoringPembelajaran::whereMonth('tanggal', $carbon->translatedFormat('m'))->whereYear('tanggal', '=', $carbon->translatedFormat('Y'))->select('id', 'tanggal')->with('kehadiranPembelajarans')->get();
            $jml = MonitoringPembelajaran::whereMonth('tanggal', $carbon->translatedFormat('m'))->whereYear('tanggal', '=', $carbon->translatedFormat('Y'))->select('id', 'tanggal')->where('status_validasi', 'tidak terlaksana')->count();
            array_push($this->grafikJmlPembelajaran, ['bulan' => $carbon->translatedFormat('F Y'), 'jml' => $jml]);


            $tidakHadir = 0;
            foreach ($monitoring as $m) {
                $tidakHadir = $tidakHadir + $m->kehadiranPembelajarans->where('status', '!=', 'hadir')->count();
            }
            array_push($this->grafikJmlTidakHadir, $tidakHadir);
        }

        $this->filterBulan = "02-" . \Carbon\Carbon::now()->translatedFormat('m-Y');

        //Ambil Jumlah Pembelajaran Terlaksana dan tidak terlaksana

        $this->tgl = new DateTime($this->filterBulan);

        $this->pembelajaran = MonitoringPembelajaran::whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'))->select('id', 'tanggal', 'status_validasi')->get();

        $kegiatan = Kegiatan::all();
        $this->jumlahKegiatan = [];
        foreach ($kegiatan as $k) {
            $jadwal = JadwalKegiatan::where('kegiatan_id', $k->id)->get();
            $jmlKegiatan = 0;
            foreach ($jadwal as $j) {
                if ($k->narasumber == 0) {
                    // $jmlKegiatan = $j->with(['monitoringKegiatan' => function ($query) {
                    //     $query->whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'));
                    // }])->get();
                    $jmlKegiatan = $jmlKegiatan + MonitoringKegiatan::where('jadwal_kegiatan_id', $j->id)->whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'))->select('id', 'tanggal')->count();
                    // dd($jmlKegiatan);
                } else {

                    $jmlKegiatan = $jmlKegiatan + MonitoringKegnas::where('jadwal_kegiatan_id', $j->id)->whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'))->select('id', 'tanggal')->count();
                }
            }
            array_push($this->jumlahKegiatan, ['kegiatan' => $k->nama, 'jml' => $jmlKegiatan]);
        }

        $hadir = 0;
        $izin = 0;
        $sakit = 0;
        $alfa = 0;
        $dinasDalam = 0;
        $dinasLuar = 0;

        foreach ($this->pembelajaran as $p) {
            $hadir = $hadir + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'hadir')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $izin = $izin + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'izin')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $sakit = $sakit + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'sakit')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $alfa = $alfa + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'alfa')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $dinasDalam = $dinasDalam + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'dinas dalam')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $dinasLuar = $dinasLuar + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'dinas luar')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
        }

        array_push($this->presensi, ['hadir' => $hadir, 'izin' => $izin, 'sakit' => $sakit, 'alfa' => $alfa, 'dd' => $dinasDalam, 'dl' => $dinasLuar]);


        $hadir = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'hadir')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'hadir')->count();
        $izin = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'izin')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'izin')->count();
        $sakit = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'sakit')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'sakit')->count();
        $alfa = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'alfa')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'alfa')->count();
        $dinasDalam = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas dalam')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas dalam')->count();
        $dinasLuar = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas luar')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas luar')->count();

        array_push($this->presensiKegiatan, ['hadir' => $hadir, 'izin' => $izin, 'sakit' => $sakit, 'alfa' => $alfa, 'dd' => $dinasDalam, 'dl' => $dinasLuar]);
    }

    public function updatedFilterBulan()
    {
        $this->presensi = [];
        $this->tgl = new DateTime($this->filterBulan);

        $kegiatan = Kegiatan::all();
        $this->jumlahKegiatan = [];
        foreach ($kegiatan as $k) {
            $jadwal = JadwalKegiatan::where('kegiatan_id', $k->id)->get();
            $jmlKegiatan = 0;
            foreach ($jadwal as $j) {
                if ($k->narasumber == 0) {
                    // $jmlKegiatan = $j->with(['monitoringKegiatan' => function ($query) {
                    //     $query->whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'));
                    // }])->get();
                    $jmlKegiatan = $jmlKegiatan + MonitoringKegiatan::where('jadwal_kegiatan_id', $j->id)->whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'))->select('id', 'tanggal')->count();
                    // dd($jmlKegiatan);
                } else {

                    $jmlKegiatan = $jmlKegiatan + MonitoringKegnas::where('jadwal_kegiatan_id', $j->id)->whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'))->select('id', 'tanggal')->count();
                }
            }
            array_push($this->jumlahKegiatan, ['kegiatan' => $k->nama, 'jml' => $jmlKegiatan]);
        }

        $this->pembelajaran = MonitoringPembelajaran::whereMonth('tanggal', $this->tgl->format('m'))->whereYear('tanggal', '=', $this->tgl->format('Y'))->select('id', 'tanggal', 'status_validasi')->get();

        $hadir = 0;
        $izin = 0;
        $sakit = 0;
        $alfa = 0;
        $dinasDalam = 0;
        $dinasLuar = 0;

        foreach ($this->pembelajaran as $p) {
            $hadir = $hadir + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'hadir')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $izin = $izin + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'izin')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $sakit = $sakit + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'sakit')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $alfa = $alfa + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'alfa')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $dinasDalam = $dinasDalam + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'dinas dalam')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            $dinasLuar = $dinasLuar + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $p->id)->where('status', 'dinas luar')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
        }

        array_push($this->presensi, ['hadir' => $hadir, 'izin' => $izin, 'sakit' => $sakit, 'alfa' => $alfa, 'dd' => $dinasDalam, 'dl' => $dinasLuar]);

        $hadir = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'hadir')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'hadir')->count();
        $izin = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'izin')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'izin')->count();
        $sakit = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'sakit')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'sakit')->count();
        $alfa = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'alfa')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'alfa')->count();
        $dinasDalam = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas dalam')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas dalam')->count();
        $dinasLuar = KehadiranKegiatan::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas luar')->count() + KehadiranKegnas::whereMonth('created_at', $this->tgl->format('m'))->whereYear('created_at', '=', $this->tgl->format('Y'))->where('status', 'dinas luar')->count();

        array_push($this->presensiKegiatan, ['hadir' => $hadir, 'izin' => $izin, 'sakit' => $sakit, 'alfa' => $alfa, 'dd' => $dinasDalam, 'dl' => $dinasLuar]);
        $this->dispatchBrowserEvent('refresh-chart');
    }
    public function render()
    {
        return view('livewire.persentase-dashboard',);
    }
}
