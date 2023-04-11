<?php

namespace App\Http\Livewire;

use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;
use App\Models\TahunAkademik;
use DateTime;
use DatePeriod;
use DateInterval;
use Livewire\Component;
use Carbon\CarbonPeriod;
use DateTimeZone;

class PersentaseDashboard extends Component
{
    public $bulan = [];
    public $filterBulan;
    public $pembelajaran;
    public $presensi = [];
    public $grafikJmlPembelajaran = [];
    public $grafikJmlTidakHadir = [];

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
        $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $end->format('d-m-Y'));
        // $bulan = strftime('%B %Y', strtotime($dt->format('d-m-Y')));
        $bulan = $carbon->translatedFormat('F Y');
        $tanggal = $dt->format('d-m-Y');

        array_push($this->bulan, ['bulan' => $bulan, 'tanggal' => $tanggal,]);

        //Ambil Bulan Tahun dan jumlah Pembelajaran tidak terlaksana
        foreach ($this->bulan as $b) {
            $carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $b['tanggal']);
            // $bulan = strftime('%B %Y', strtotime($dt->format('d-m-Y')));
            // $tgl = new DateTime($b['tanggal']);
            $monitoring = MonitoringPembelajaran::whereMonth('tanggal', $carbon->translatedFormat('m'))->whereYear('tanggal', '=', $carbon->translatedFormat('Y'))->select('id', 'tanggal')->get();
            $jml = $monitoring->count();
            array_push($this->grafikJmlPembelajaran, ['bulan' => $carbon->translatedFormat('F Y'), 'jml' => $jml]);
            $tidakHadir = 0;
            foreach ($monitoring as $m) {
                $tidakHadir = $tidakHadir + KehadiranPembelajaran::where('monitoring_pembelajaran_id', $m->id)->where('status', '!=', 'hadir')->select('id', 'status', 'monitoring_pembelajaran_id')->count();
            }
            array_push($this->grafikJmlTidakHadir, $tidakHadir);
        }

        // dd($this->grafikJmlTidakHadir);
        $this->filterBulan = "02-" . \Carbon\Carbon::now()->translatedFormat('m-Y');


        //Ambil Jumlah Pembelajaran Terlaksana dan tidak terlaksana

        $tgl = new DateTime($this->filterBulan);

        $this->pembelajaran = MonitoringPembelajaran::whereMonth('tanggal', $tgl->format('m'))->whereYear('tanggal', '=', $tgl->format('Y'))->select('id', 'tanggal', 'status_validasi')->get();



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
        // dd($this->presensi);
        // dd($pembelajaran->count());
        // dd($tgl->format('m'));
        // dd($this->pembelajaran->where('status_validasi', '!=', 'valid'));
    }

    public function updatedFilterBulan()
    {
        $this->presensi = [];
        $tgl = new DateTime($this->filterBulan);

        $this->pembelajaran = MonitoringPembelajaran::whereMonth('tanggal', $tgl->format('m'))->whereYear('tanggal', '=', $tgl->format('Y'))->select('id', 'tanggal', 'status_validasi')->get();

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
        $this->dispatchBrowserEvent('refresh-chart');
    }
    public function render()
    {
        return view('livewire.persentase-dashboard');
    }
}
