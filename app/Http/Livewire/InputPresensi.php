<?php

namespace App\Http\Livewire;

use DateTime;
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
use App\Models\MonitoringPembelajaranNew;

class InputPresensi extends Component
{
    //pagination
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //filter
    public $filterKelas = '', $filterMapel;
    //menampung data siswa berdasarkan kelas
    public $student;
    //menampung hari ini
    public $day;
    //memberitahu sistem apakah data akan diupdate atau inputan baru
    public $update = false;
    public $editPresensi;
    //menampung jadwal pelajaran dan jadwal pengganti
    public $mapel, $mapelPengganti, $guru_id;
    //atribut inputan
    public $tanggal, $waktu_mulai, $waktu_berakhir, $topik;
    //menampung kehadiran siswa
    public $presensi = [];
    public $minDate;

    public function mount()
    {
        if (TahunAkademik::select('id')->where('status', 'aktif')->first()) {
            $this->minDate = TahunAkademik::select('id', 'tgl_mulai')->where('status', 'aktif')->first()->tgl_mulai;
        }
        //Set default kelas pada tahun akademik yang aktif 
        if (TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()) {
            $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;
        } else {
            $this->filterKelas = '';
        }

        //mengambil semua data siswa berdasarkan kelas default
        $this->student = [];
        if (Kelas::where('id', $this->filterKelas)->first()) {
            $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->get();
        }
        // $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas->orderBy('nama', 'asc')->all();

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //mengambil nama hari 
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');

        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');

        if (Auth::user()->role === 'admin') {
            //mengambil jadwal pelajaran berdasarkan pada kelas default
            $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();

            //mengambil Jadwal Pengganti
            $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
        } else if (Auth::user()->role === 'guru') {
            //mengambil jadwal pelajaran berdasarkan pada kelas default dan id guru
            $this->mapel = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
            // dD($this->mapel);
            //mengambil Jadwal Pengganti
            $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->whereRelation('jadwalPelajaran', 'guru_id', Auth::user()->guru->id)->get();
            // dd($this->mapelPengganti);
        }


        //cek apakah ada jadwal
        if (count($this->mapel) !== 0 or count($this->mapelPengganti) !== 0) {

            //kalau ada jadwal mapel maka set default filter mapel pertama
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel[0]->id;
                $this->guru_id = $this->mapel[0]->guru->id;
                $mapel_id = $this->mapel[0]->mata_pelajaran_id;
            } else {
                //kalau tidak set default mapel dari jadwal pengganti
                $this->filterMapel = $this->mapelPengganti[0]->id;
                $this->guru_id = $this->mapelPengganti[0]->guru->id;
                $mapel_id = $this->mapelPengganti[0]->mata_pelajaran_id;
            }

            //Cek apakah presensi sudah diinputkan
            if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
                //ambil data
                $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();

                //set data berdasarkan data yang sudah diinputkan
                $this->editPresensi = $monitoring->monitoring_pembelajaran_id;
                $this->tanggal = $monitoring->tanggal;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;

                //set update true, berarti data akan diupdate
                $this->update = true;

                //ambil data kehadiran siswa yang sudah diinputkan
                $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
                foreach ($kehadiran as $k) {
                    $this->presensi[$k->siswa_id] = $k->status;
                }
            } else {
                //kalau data belum diinputkan maka cek apakah ada jadwal mapel
                if (count($this->mapel) !== 0) {
                    //set waktu belajar berdasarkan jadwal
                    $this->waktu_mulai = substr($this->mapel[0]->waktu_mulai, 0, -3);
                    $this->waktu_berakhir = substr($this->mapel[0]->waktu_berakhir, 0, -3);
                } else {
                    //kalau jadwal mapel tidak ada maka ambil dari jadwal pengganti
                    $this->waktu_mulai = substr($this->mapelPengganti[0]->waktu_mulai, 0, -3);
                    $this->waktu_berakhir = substr($this->mapelPengganti[0]->waktu_berakhir, 0, -3);
                }
                $this->update = false;
            }
        } else {
            //set null apabila tidak ada jadwal mapel ataupun jadwal pengganti pada hari ini
            $this->filterMapel = '';
            $this->mapel = [];
            $this->mapelPengganti = [];
        }
    }

    //Rules validasi data inputan
    public function rules()
    {
        return [
            'tanggal' => 'required|date',
            'topik' => 'required',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
        ];
    }

    //Custom Errror messages for validation
    protected $messages = [
        'tanggal.required' => 'Tanggal wajib diisi !',
        'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
        'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
        'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
        'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
        'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
        'topik.required' => 'Topik/Agenda wajib diisi !',
    ];

    //mengosongkan inputan
    public function empty()
    {
        $this->topik = null;
        $this->waktu_mulai = null;
        $this->waktu_berakhir = null;
        $this->editPresensi = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //realtime validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    //method yang mengani perubahan pada filter kelas
    public function updatedFilterKelas()
    {
        //kosongkan data
        $this->empty();

        if (Auth::user()->role === 'admin') {
            //mengambil jadwal pelajaran berdasarkan pada kelas default
            $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();

            //mengambil Jadwal Pengganti
            $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
        } else if (Auth::user()->role === 'guru') {
            //mengambil jadwal pelajaran berdasarkan pada kelas default dan id guru
            $this->mapel = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
            // dD($this->mapel);
            //mengambil Jadwal Pengganti
            $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->whereRelation('jadwalPelajaran', 'guru_id', Auth::user()->guru->id)->get();
            // dd($this->mapelPengganti);
        }

        //ambil data siswa kelas yang dipilih

        $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->get();
        // $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->orderBy('nama', 'asc')->all();

        //set presensi menjadi hadir bagi setiap siswa
        $this->presensi = [];
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //cek apakah ada jadwal
        if (count($this->mapel) !== 0 or count($this->mapelPengganti) !== 0) {

            //kalau ada jadwal mapel maka set default filter mapel pertama
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel[0]->id;
                $this->guru_id = $this->mapel[0]->guru->id;
                $mapel_id = $this->mapel[0]->mata_pelajaran_id;
            } else {
                //kalau tidak set default mapel dari jadwal pengganti
                $this->filterMapel = $this->mapelPengganti[0]->id;
                $this->guru_id = $this->mapelPengganti[0]->guru->id;
                $mapel_id = $this->mapelPengganti[0]->mata_pelajaran_id;
            }

            //Cek apakah presensi sudah diinputkan
            if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
                //ambil data
                $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();

                //set data berdasarkan data yang sudah diinputkan
                $this->editPresensi = $monitoring->monitoring_pembelajaran_id;
                $this->tanggal = $monitoring->tanggal;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;

                //set update true, berarti data akan diupdate
                $this->update = true;

                //ambil data kehadiran siswa yang sudah diinputkan
                $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
                foreach ($kehadiran as $k) {
                    $this->presensi[$k->siswa_id] = $k->status;
                }
            } else {
                //kalau data belum diinputkan maka cek apakah ada jadwal mapel
                if (count($this->mapel) !== 0) {
                    //set waktu belajar berdasarkan jadwal
                    $this->waktu_mulai = substr($this->mapel[0]->waktu_mulai, 0, -3);
                    $this->waktu_berakhir = substr($this->mapel[0]->waktu_berakhir, 0, -3);
                } else {
                    //kalau jadwal mapel tidak ada maka ambil dari jadwal pengganti
                    $this->waktu_mulai = substr($this->mapelPengganti[0]->waktu_mulai, 0, -3);
                    $this->waktu_berakhir = substr($this->mapelPengganti[0]->waktu_berakhir, 0, -3);
                }
                $this->update = false;
            }
        } else {
            //set null apabila tidak ada jadwal mapel ataupun jadwal pengganti pada hari ini
            $this->filterMapel = '';
        }
    }

    public function updatedFilterMapel()
    {
        $this->empty();
        $data = JadwalPelajaran::where('id',$this->filterMapel)->first();
        $this->guru_id = $data->guru_id;
        $mapel_id = $data->mata_pelajaran_id;
        //Cek apakah presensi sudah diinputkan
        if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
            //ambil data yang sudah diinputkan
            $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();

            //set form inputan dengan nilai yang ada di database
            $this->tanggal = $monitoring->tanggal;
            $this->editPresensi = $monitoring->monitoring_pembelajaran_id;
            $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
            $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
            $this->topik = $monitoring->topik;

            //data akan diupdate
            $this->update = true;

            //set kehadiran berdasarkan database
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
        } else {
            //kalau data belum diinputkan cek apakah ada jadwal berdarkan filter yang dipilih
            if (count(JadwalPelajaran::where('hari', $this->day)->where('id', $this->filterMapel)->get()) !== 0) {
                //ambil data jadwal berdasarkan filter yang dipilih
                $jadwal = JadwalPelajaran::where('hari', $this->day)->where('id', $this->filterMapel)->first();
            } else {
                //ambil data jadwal pengganti 
                $jadwal = JadwalPengganti::where('tanggal', $this->tanggal)->where('jadwal_pelajaran_id', $this->filterMapel)->first();
            }

            //set form inputan berdasarkan jadwal
            $this->waktu_mulai = substr($jadwal->waktu_mulai, 0, -3);
            $this->waktu_berakhir = substr($jadwal->waktu_berakhir, 0, -3);

            //data tidak diupdate karena data baru
            $this->update = false;

            //set default kehadiran siswa
            $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
            $this->presensi = [];
            foreach ($this->student as $s) {
                $this->presensi[$s->id] = 'hadir';
            }
        }
    }

    public function save()
    {
        $this->validate();
        if (Auth::user()->role === 'guru') {
            // $jadwalToday = JadwalGuruPiket::where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('H:i'))->where('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('H:i'))->first();
            $status = 'belum tervalidasi';
            // if ($jadwalToday === null) {
            //     $guruPiketId = null;
            // } else {
            //     $guruPiketId = $jadwalToday->guru_id;
            // }
        } else {
            $status = 'terlaksana';
        }
        $data = JadwalPelajaran::where('id',$this->filterMapel)->first();
        $cek_presensi = MonitoringPembelajaranNew::where('tanggal',$this->tanggal)->where('kelas_id',$data->kelas_id)->where('guru_id',$data->guru_id)->where('mata_pelajaran_id',$data->mata_pelajaran_id)->first();
        if(!$cek_presensi){
            DB::transaction(function () use ($data,$status) {
                $monitoring = MonitoringPembelajaranNew::create([
                    'tanggal' => $this->tanggal,
                    'topik' => $this->topik,
                    'waktu_mulai' => $this->waktu_mulai,
                    'waktu_berakhir' => $this->waktu_berakhir,
                    'status_validasi' => $status,
                    'kelas_id' => $data->kelas_id,
                    'guru_id' => $data->guru_id,
                    'mata_pelajaran_id' => $data->mata_pelajaran_id,
                    'guru_piket_id' => null
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
        $this->update = true;
        // session()->flash('message', 'Presensi berhasil diinputkan !');
        $this->dispatchBrowserEvent('alert-save',['info' => 'Berhasil', 'message' => 'Presensi berhasil diinputkan!']);
    }

    public function update()
    {
        $this->validate();
        DB::transaction(function () {
            MonitoringPembelajaranNew::where('monitoring_pembelajaran_id', $this->editPresensi)->update([
                'tanggal' => $this->tanggal,
                'topik' => $this->topik,
                'waktu_mulai' => $this->waktu_mulai,
                'waktu_berakhir' => $this->waktu_berakhir,
            ]);
            foreach ($this->presensi as $key => $value) {
                KehadiranPembelajaran::where('monitoring_pembelajaran_id', $this->editPresensi)->where('siswa_id', $key)->update([
                    'status' => $value,
                ]);
            }
        });
        // session()->flash('message', 'Presensi berhasil diupdate !');
        
        $this->dispatchBrowserEvent('alert-update',['info' => 'Berhasil', 'message' => 'Presensi berhasil diupdate!']);
    }

    public function updatedTanggal()
    {
        $this->day = \Carbon\Carbon::createFromFormat('Y-m-d', $this->tanggal)->translatedFormat('l');
        //kosongkan data
        $this->empty();
        //mengambil jadwal pelajaran berdasarkan pada kelas default
        $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();

        //mengambil Jadwal Pengganti
        $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();


        //ambil data siswa kelas yang dipilih

        $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->get();
        // $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->orderBy('nama', 'asc')->all();

        //set presensi menjadi hadir bagi setiap siswa
        $this->presensi = [];
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //cek apakah ada jadwal
        if (count($this->mapel) !== 0 or count($this->mapelPengganti) !== 0) {

            //kalau ada jadwal mapel maka set default filter mapel pertama
            if (count($this->mapel) !== 0) {
                $this->filterMapel = $this->mapel[0]->id;
                $this->guru_id = $this->mapel[0]->guru->id;
                $mapel_id = $this->mapel[0]->mata_pelajaran_id;
            } else {
                //kalau tidak set default mapel dari jadwal pengganti
                $this->filterMapel = $this->mapelPengganti[0]->id;
                $this->guru_id = $this->mapelPengganti[0]->guru->id;
                $mapel_id = $this->mapelPengganti[0]->mata_pelajaran_id;
            }

            //Cek apakah presensi sudah diinputkan
            if (MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first()) {
                //ambil data
                $monitoring = MonitoringPembelajaranNew::where('mata_pelajaran_id', $mapel_id)->where('guru_id',$this->guru_id)->where('kelas_id',$this->filterKelas)->where('tanggal', $this->tanggal)->first();

                //set data berdasarkan data yang sudah diinputkan
                $this->editPresensi = $monitoring->monitoring_pembelajaran_id;
                $this->tanggal = $monitoring->tanggal;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;

                //set update true, berarti data akan diupdate
                $this->update = true;

                //ambil data kehadiran siswa yang sudah diinputkan
                $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->monitoring_pembelajaran_id)->get()->all();
                foreach ($kehadiran as $k) {
                    $this->presensi[$k->siswa_id] = $k->status;
                }
            } else {
                //kalau data belum diinputkan maka cek apakah ada jadwal mapel
                if (count($this->mapel) !== 0) {
                    //set waktu belajar berdasarkan jadwal
                    $this->waktu_mulai = substr($this->mapel[0]->waktu_mulai, 0, -3);
                    $this->waktu_berakhir = substr($this->mapel[0]->waktu_berakhir, 0, -3);
                } else {
                    //kalau jadwal mapel tidak ada maka ambil dari jadwal pengganti
                    $this->waktu_mulai = substr($this->mapelPengganti[0]->waktu_mulai, 0, -3);
                    $this->waktu_berakhir = substr($this->mapelPengganti[0]->waktu_berakhir, 0, -3);
                }
                $this->update = false;
            }
        } else {
            //set null apabila tidak ada jadwal mapel ataupun jadwal pengganti pada hari ini
            $this->filterMapel = '';
        }
    }

    public function render()
    {
        if (Auth::user()->role === 'admin') {
            //mengambil jadwal pelajaran berdasarkan pada kelas default
            $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();

            //mengambil Jadwal Pengganti
            $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
        } else if (Auth::user()->role === 'guru') {
            //mengambil jadwal pelajaran berdasarkan pada kelas default dan id guru
            $this->mapel = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
            // dD($this->mapel);
            //mengambil Jadwal Pengganti
            $this->mapelPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->whereRelation('jadwalPelajaran', 'guru_id', Auth::user()->guru->id)->get();
            // dd($this->mapelPengganti);
        }
        if (Kelas::where('id', $this->filterKelas)->first()) {
            $siswa = Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->paginate(10);
        } else {
            $siswa = [];
        }
        return view('livewire.input-presensi', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
            'siswa' => $siswa,
            'jadwal_pengganti' => $this->mapelPengganti
        ]);
    }
}