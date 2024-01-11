<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalGuruPiket;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;
use App\Models\MonitoringPembelajaranNew;

class ValidasiPembelajaran extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //filter
    public $filterKelas = '';
    public $jadwal, $day, $tanggal, $jadwalPengganti;
    //menampung data siswa berdasarkan kelas
    public $student;
    //atribut inputan
    public $waktu_mulai, $waktu_berakhir, $topik;
    //menampung kehadiran siswa
    public $presensi = [], $keterangan, $status, $editPresensi;
    public $update = false;
    public $jadwal_id;
    public $minDate;

    public function mount()
    {
        //mengambil nama hari 
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        $this->filterKelas = '';
        if (TahunAkademik::select('id')->where('status', 'aktif')->first()) {
            $this->minDate = TahunAkademik::select('id', 'tgl_mulai')->where('status', 'aktif')->first()->tgl_mulai;
        }
        if (TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()) {
            $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;
        }
        $this->jadwal_id = '';

        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
    }

    //Custom Errror messages for validation
    protected $messages = [
        'keterangan.required' => 'Keterangan wajib diisi !',
        'topik.required' => 'Topik wajib diisi !',
    ];

    public function updatedFilterKelas()
    {

        if (Auth::user()->role === 'guru') {
            $jadwalToday = JadwalGuruPiket::where('guru_id', Auth::user()->guru->id)->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->first();
            // ->where(function ($query) {
            //     $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('H:i'))->where('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('H:i'));
            // })->first();
            if ($jadwalToday === null) {
                $this->jadwal = [];
                $this->jadwalPengganti = [];
            } else {
                //Mengambil jadwal hari ini
                $this->jadwal = DB::table('jadwal_pelajarans as a')
                                ->where('a.hari', $this->day)
                                ->where('a.waktu_mulai', '>=', $jadwalToday->waktu_mulai)
                                ->where('a.waktu_berakhir', '<=', $jadwalToday->waktu_berakhir)
                                ->where('a.kelas_id', $this->filterKelas)
                                ->leftjoin('kelas as b','b.id','a.kelas_id')
                                ->leftjoin('gurus as c','c.id','a.guru_id')
                                ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                                ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                                    $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                                        ->on('e.kelas_id','a.kelas_id')
                                        ->on('e.guru_id','a.guru_id')
                                        ->where('e.tanggal', $this->tanggal);
                                })
                                ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.*')
                                ->get();
            }
        } else {
            //Mengambil jadwal hari ini
            $this->jadwal = DB::table('jadwal_pelajarans as a')
                            ->where('a.hari', $this->day)
                            ->where('a.kelas_id', $this->filterKelas)
                            ->leftjoin('kelas as b','b.id','a.kelas_id')
                            ->leftjoin('gurus as c','c.id','a.guru_id')
                            ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                            ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                                $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                                    ->on('e.kelas_id','a.kelas_id')
                                    ->on('e.guru_id','a.guru_id')
                                    ->where('e.tanggal', $this->tanggal);
                            })
                            ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.*')
                            ->get();
        }
    }

    public function updatedTanggal()
    {
        $this->day = \Carbon\Carbon::createFromFormat('Y-m-d', $this->tanggal)->translatedFormat('l');

        //Mengambil jadwal hari ini
        $this->jadwal = DB::table('jadwal_pelajarans as a')
                        ->where('a.hari', $this->day)
                        ->where('a.kelas_id', $this->filterKelas)
                        ->leftjoin('kelas as b','b.id','a.kelas_id')
                        ->leftjoin('gurus as c','c.id','a.guru_id')
                        ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                        ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                            $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                                ->on('e.kelas_id','a.kelas_id')
                                ->on('e.guru_id','a.guru_id')
                                ->where('e.tanggal', $this->tanggal);
                        })
                        ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.*')
                        ->get();
    }

    public function empty()
    {
        $this->editPresensi = null;
        $this->jadwal = DB::table('jadwal_pelajarans as a')
                        ->where('a.hari', $this->day)
                        ->where('a.kelas_id', $this->filterKelas)
                        ->leftjoin('kelas as b','b.id','a.kelas_id')
                        ->leftjoin('gurus as c','c.id','a.guru_id')
                        ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                        ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                            $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                                ->on('e.kelas_id','a.kelas_id')
                                ->on('e.guru_id','a.guru_id')
                                ->where('e.tanggal', $this->tanggal);
                        })
                        ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.*')
                        ->get();

        //Get Jadwal Pengganti
        $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetPage();
    }


    public function showId($id)
    {
        //mengambil semua data siswa berdasarkan kelas default
        $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas;

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }
        $data = JadwalPelajaran::where('id',$id)->first();

        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $data->mata_pelajaran_id)->where('guru_id',$data->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $data->mata_pelajaran_id)->where('guru_id',$data->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            // $this->tanggal = $monitoring->tanggal;
            $this->topik = $monitoring->topik;
            $this->keterangan = $monitoring->keterangan;
            $this->status = $monitoring->status_validasi;

            //ambil data kehadiran siswa yang sudah diinputkan
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
        } else {
            $this->topik = '';
        }

        $this->dispatchBrowserEvent('show-modal');
    }

    public function presensi($id)
    {
        //mengambil semua data siswa berdasarkan kelas default
        $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas;

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        $this->jadwal_id = $id;

        $jadwal = JadwalPelajaran::where('id', $id)->first();
        $this->waktu_mulai = $jadwal->waktu_mulai;
        $this->waktu_berakhir = $jadwal->waktu_berakhir;


        // dd($this->jadwal_id);

        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)->where('guru_id',$jadwal->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)->where('guru_id',$jadwal->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            // $this->tanggal = $monitoring->tanggal;
            $this->topik = $monitoring->topik;
            $this->editPresensi = $monitoring->monitoring_pembelajaran_id;
            $this->keterangan = $monitoring->keterangan;

            //ambil data kehadiran siswa yang sudah diinputkan
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
            $this->update = true;
        } else {
            $this->topik = '';
            $this->keterangan = '';
            $this->update = false;
        }
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function showValid($id)
    {
        $data = JadwalPelajaran::where('id',$id)->first();
        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $data->mata_pelajaran_id)->where('guru_id',$data->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $data->mata_pelajaran_id)->where('guru_id',$data->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            $this->editPresensi = $monitoring->monitoring_pembelajaran_id;
        }
        $this->dispatchBrowserEvent('show-valid-modal');
    }


    public function valid()
    {
        if (Auth::user()->role === 'guru') {
            MonitoringPembelajaranNew::where('monitoring_pembelajaran_id', $this->editPresensi)->update([
                'status_validasi' => 'terlaksana',
                'keterangan' => null,
                'guru_piket_id' => Auth::user()->guru->id
            ]);
        } else {
            MonitoringPembelajaranNew::where('monitoring_pembelajaran_id', $this->editPresensi)->update([
                'status_validasi' => 'terlaksana',
                'keterangan' => null,
                'guru_piket_id' => null
            ]);
        }

        // session()->flash('message', 'Presensi berhasil diperbarui !');
        $this->empty();
        // $this->dispatchBrowserEvent('close-valid-modal');
        $this->dispatchBrowserEvent('alert-valid',['info' => 'Berhasil', 'message' => 'Validasi berhasil disimpan!']);
    
    }

    public function tidakValid()
    {
        $this->validate([
            'keterangan' => 'required',
            'topik' => 'required',
            'presensi' => 'required'
        ]);
        if (Auth::user()->role === 'guru') {
            $guruPiketId = Auth::user()->guru->id;
        } else {
            $guruPiketId = null;
        }

        $data = JadwalPelajaran::where('id',$this->jadwal_id)->first();

        if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $data->mata_pelajaran_id)->where('guru_id',$data->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
            // dd("MASUK SINI HARUSNYA");
            // MonitoringPembelajaran::where('id', $this->editPresensi)
            DB::transaction(function () use ($data,$guruPiketId) {
                MonitoringPembelajaranNew::where('mata_pelajaran_id', $data->mata_pelajaran_id)->where('guru_id',$data->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->update([
                    'keterangan' => $this->keterangan,
                    'topik' => $this->topik,
                    'status_validasi' => 'tidak terlaksana',
                    'guru_piket_id' => $guruPiketId
                ]);
                foreach ($this->presensi as $key => $value) {
                    KehadiranPembelajaran::where('monitoring_pembelajaran_id', $this->editPresensi)->where('siswa_id', $key)->update([
                        'status' => $value,
                    ]);
                }
            });
        } else {
            // dd('KOK MASUK SINI SIH');
            DB::transaction(function () use($guruPiketId,$data) {
                $monitoring = MonitoringPembelajaranNew::create([
                    'tanggal' => $this->tanggal,
                    'topik' => $this->topik,
                    'waktu_mulai' => $this->waktu_mulai,
                    'waktu_berakhir' => $this->waktu_berakhir,
                    'status_validasi' =>'tidak terlaksana',
                    'kelas_id' => $data->kelas_id,
                    'guru_id' => $data->guru_id,
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'guru_piket_id' => $guruPiketId,
                    'keterangan' => $this->keterangan
                ]);
        
                foreach ($this->presensi as $key => $value) {
                    KehadiranPembelajaran::create([
                        'siswa_id' => $key,
                        'status' => $value,
                        'monitoring_pembelajaran_id' => $monitoring->monitoring_pembelajaran_id
                    ]);
                }
            });
        }
        // session()->flash('message', 'Presensi berhasil diperbarui !');
        // $this->dispatchBrowserEvent('close-edit-modal');
        $this->dispatchBrowserEvent('alert-tidak-valid',['info' => 'Berhasil', 'message' => 'Validasi berhasil disimpan!']);
        $this->empty();
    }

    public function render()
    {
        $siswa = [];
        if (Kelas::where('id', $this->filterKelas)->first()) {
            $siswa = Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10);
        }
        if (Auth::user()->role === 'guru') {
            $jadwalToday = JadwalGuruPiket::where('guru_id', Auth::user()->guru->id)->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->first();
            if ($jadwalToday === null) {
                $this->jadwal = [];
                $this->jadwalPengganti = [];
            } else {
                //Mengambil jadwal hari ini
                $this->jadwal = DB::table('jadwal_pelajarans as a')
                                ->where('a.hari', $this->day)
                                ->where('a.waktu_mulai', '>=', $jadwalToday->waktu_mulai)
                                ->where('a.waktu_berakhir', '<=', $jadwalToday->waktu_berakhir)
                                ->where('a.kelas_id', $this->filterKelas)
                                ->leftjoin('kelas as b','b.id','a.kelas_id')
                                ->leftjoin('gurus as c','c.id','a.guru_id')
                                ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                                ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                                    $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                                        ->on('e.kelas_id','a.kelas_id')
                                        ->on('e.guru_id','a.guru_id')
                                        ->where('e.tanggal', $this->tanggal);
                                })
                                ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.*')
                                ->get();                
            }
        } else {
            //Mengambil jadwal hari ini
            $this->jadwal = DB::table('jadwal_pelajarans as a')
                            ->where('a.hari', $this->day)
                            // ->where('a.waktu_mulai', '>=', $jadwalToday->waktu_mulai)
                            // ->where('a.waktu_berakhir', '<=', $jadwalToday->waktu_berakhir)
                            ->where('a.kelas_id', $this->filterKelas)
                            ->leftjoin('kelas as b','b.id','a.kelas_id')
                            ->leftjoin('gurus as c','c.id','a.guru_id')
                            ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                            ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                                $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                                    ->on('e.kelas_id','a.kelas_id')
                                    ->on('e.guru_id','a.guru_id')
                                    ->where('e.tanggal', $this->tanggal);
                            })
                            ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.*')
                            ->get();
                            // $this->jadwal = DB::table('jadwal_pelajarans as a')
                            // ->where('a.hari', $this->day)
                            // ->where('a.kelas_id', $this->filterKelas)
                            // ->leftjoin('kelas as b','b.id','a.kelas_id')
                            // ->leftjoin('gurus as c','c.id','a.guru_id')
                            // ->leftjoin('mata_pelajarans as d','d.id','a.mata_pelajaran_id')
                            // ->leftjoin('monitoring_pembelajaran_news as e',function($join){
                            //     $join->on('e.mata_pelajaran_id','a.mata_pelajaran_id')
                            //         ->on('e.kelas_id','a.kelas_id')
                            //         ->on('e.guru_id','a.guru_id')
                            //         ->where('e.tanggal',$this->tanggal);
                            // })
                            // ->select('a.id','a.waktu_mulai','a.waktu_berakhir','a.kelas_id','a.mata_pelajaran_id','a.guru_id','b.nama as kelas','c.nama as guru','d.nama as mata_pelajaran','e.topik', 'e.status_validasi')
                            // ->get();
        }
        return view('livewire.validasi-pembelajaran', [
            'jadwal' => $this->jadwal,
            'jadwalPengganti' => $this->jadwalPengganti,
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'siswa' => $siswa
        ]);
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }
}