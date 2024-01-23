<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\WaliAsrama;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\JadwalGuruPiket;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $tanggal, $hari, $tahunAkademik, $siswaId;
    public function index()
    {
        $this->hari = \Carbon\Carbon::now()->translatedFormat('l');
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        if (TahunAkademik::where('status', 'aktif')->first()) {
            $this->tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
        } else {
            $this->tahunAkademik = null;
        }
        if (Auth::user()->role === 'siswa') {
            $this->siswaId = Auth::user()->siswa->id;
            // $data = Auth::user()->siswa->id;
            $jadwal = Siswa::where('id', $this->siswaId)->select('id',)->with(['kelas' => function ($query) {
                $query->whereRelation('tahunAkademik', 'status', 'aktif')->with(['jadwalPelajarans' => function ($sql) {
                    $sql->where('hari', $this->hari)->get();
                }])->get();
            }])->first();


            $data = Siswa::where('user_id', auth('sanctum')->user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
                $query->whereRelation('tahunAkademik', 'status', 'aktif')->with(['angkatan' => function ($query) {
                    $query->with(['jadwalKegiatans' => function ($query) {
                        $query->where('tahun_akademik_id', $this->tahunAkademik)->with('kegiatan')->where('hari', '=', 'Setiap Hari')->orwhere('hari', '=', $this->hari)->with(['monitoringKegnas' => function ($query) {
                            if ($query) {
                                $query->with('narasumber')->where('tanggal', $this->tanggal)->with(['kehadiranKegnas' => function ($query) {
                                    if ($query) {
                                        $query->where('siswa_id', $this->siswaId);
                                    } else {
                                        $query;
                                    }
                                }]);
                            } else {
                                $query;
                            }
                        }])->with(['monitoringKegiatan' => function ($query) {
                            if ($query) {
                                $query->where('tanggal', $this->tanggal)->with(['kehadiranKegiatan' => function ($query) {
                                    if ($query) {
                                        $query->where('siswa_id', $this->siswaId);
                                    } else {
                                        $query;
                                    }
                                }]);
                            } else {
                                $query;
                            }
                        }]);;
                    }]);
                }]);
            }])->get();
            // dd($data[0]->kelas->first()->angkatan->jadwalKegiatans);
            if ($jadwal->kelas->first()) {
                $jadwalPelajaran = $jadwal->kelas->first()->jadwalPelajarans;
            } else {
                $jadwalPelajaran = [];
            }

            if ($data[0]->kelas->first()) {
                $jadwalKegiatan = $data[0]->kelas->first()->angkatan->jadwalKegiatans;
            } else {
                $jadwalKegiatan = [];
            }
            return view('pages.siswa.dashboard', [
                'title' => 'Dashboard',
                'jadwal' => $jadwalPelajaran,
                'jadwalKegiatan' => $jadwalKegiatan
            ]);
        } else if (Auth::user()->role === 'admin') {
            $kelasAktif = TahunAkademik::where('status', 'aktif')->select('id')->first();
            if ($kelasAktif) {
                $kelas = $kelasAktif->kelas->count();
            } else {
                $kelas = 0;
            }

            return view('pages.admin.dashboard', [
                'title' => 'Dashboard',
                'siswaAktif' => Siswa::where('status', 'belum lulus')->select('id')->count(),
                'guruAktif' => Guru::where('status', 'aktif')->select('id')->count(),
                'kelasAktif' => $kelas,
                'waliAsrama' => WaliAsrama::where('status', 'aktif')->select('id')->count()
            ]);
        } else if (Auth::user()->role === 'guru') {
            $kelasAktif = [];
            $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
            foreach ($data as $d) {
                array_push($kelasAktif, $d->id);
            }
            //mengambil nama hari 
            $day = \Carbon\Carbon::now()->translatedFormat('l');

            $jadwal = DB::table('jadwal_pelajarans as a')
                    ->where('a.guru_id',Auth::user()->guru->id)
                    ->whereIn('a.kelas_id',$kelasAktif)
                    ->where('a.hari', $day)
                    ->leftjoin('mata_pelajarans as b','b.id','a.mata_pelajaran_id')
                    ->leftjoin('kelas as c','c.id','a.kelas_id')
                    ->leftjoin('monitoring_pembelajaran_news as d',function($join){
                        $join->on('d.mata_pelajaran_id','a.mata_pelajaran_id')
                            ->on('d.guru_id','a.guru_id')
                            ->where('d.tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'));
                    })
                    ->select('a.waktu_mulai','a.waktu_berakhir','b.nama as mata_pelajaran','c.nama as kelas','d.status_validasi')
                    ->orderBy('a.waktu_mulai')
                    ->get();
                    
            //Ambil Jadwal Piket
            if (JadwalGuruPiket::where('guru_id', Auth::user()->guru->id)->first()) {
                $jadwalPiket = JadwalGuruPiket::where('guru_id', Auth::user()->guru->id)->first();
            } else {
                $jadwalPiket = null;
            }

            return view('pages.guru.dashboard', [
                'title' => 'Dashboard',
                'jadwal' => $jadwal,
                'jadwalPiket' => $jadwalPiket
            ]);
        } else if (Auth::user()->role === 'pimpinan') {
            return view('pages.admin.dashboard', [
                'title' => 'Dashboard',
                'siswaAktif' => Siswa::where('status', 'belum lulus')->select('id')->count(),
                'guruAktif' => Guru::where('status', 'aktif')->select('id')->count(),
                'kelasAktif' => TahunAkademik::where('status', 'aktif')->select('id')->first()->kelas->count(),
                'waliAsrama' => WaliAsrama::where('status', 'aktif')->select('id')->count()
            ]);
        } else if (Auth::user()->role === 'wali_asrama') {

            $hari = \Carbon\Carbon::now()->translatedFormat('l');
            $angkatan = WaliAsrama::where('user_id', Auth::user()->id)->first()->angkatans->where('status', 'belum lulus')->first();
            $jadwal = JadwalKegiatan::where('angkatan_id', $angkatan->id)->where('tahun_akademik_id', $this->tahunAkademik)->where('hari', '=', 'Setiap Hari')->orwhere('hari', '=', $hari)->with('kegiatan')->with('monitoringKegnas')->with('monitoringKegiatan')->get();
            return view('pages.wali_asrama.dashboard', [
                'title' => 'Dashboard',
                'angkatan' => $angkatan->nama,
                'jadwal' => $jadwal
            ]);
        } else {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
    }

    public function profile()
    {
        return view('profile', [
            'title' => 'Halaman Profile'
        ]);
    }
}