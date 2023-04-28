<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\KehadiranKegnas;
use App\Models\MonitoringKegnas;
use App\Models\Narasumber;

class PresensiKegiatanNarasumber extends Component
{
    //pagination
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //filter
    public $filterKelas = '', $hari;
    //menampung data siswa berdasarkan kelas
    public $student;
    //menampung hari ini
    public $today;
    //memberitahu sistem apakah data akan diupdate atau inputan baru
    public $update = false;
    public $editPresensi;
    //menampung jadwal pelajaran dan jadwal pengganti
    public $jadwal;
    //atribut inputan
    public $tanggal, $waktu_mulai, $waktu_berakhir, $topik, $narasumber_id;
    //menampung kehadiran siswa
    public $presensi = [];
    public $kegiatan;

    public function mount($kegiatan)
    {
        //Set default kelas pada tahun akademik yang aktif 
        $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;

        //mengambil semua data siswa berdasarkan kelas default
        $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->get();
        // $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas->orderBy('nama', 'asc')->all();

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //mengambil nama hari 
        $this->today = \Carbon\Carbon::now()->translatedFormat('l');

        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');

        $this->kegiatan = $kegiatan;

        $angkatan_id = Kelas::find($this->filterKelas)->angkatan_id;
        $this->jadwal = JadwalKegiatan::where('kegiatan_id', $kegiatan->id)->where('angkatan_id', $angkatan_id)->first();
        if ($this->jadwal) {
            $this->hari = $this->jadwal->hari;
            $this->waktu_mulai = substr($this->jadwal->waktu_mulai, 0, -3);
            $this->waktu_berakhir = substr($this->jadwal->waktu_berakhir, 0, -3);
            $this->update = false;

            //Cek apakah jadwal sudah diinputkan
            if (MonitoringKegnas::where('jadwal_kegiatan_id', $this->jadwal->id)->where('tanggal', $this->tanggal)->first()) {
                //ambil data
                $monitoring = MonitoringKegnas::where('jadwal_kegiatan_id', $this->jadwal->id)->where('tanggal', $this->tanggal)->first();

                //set data berdasarkan data yang sudah diinputkan
                $this->editPresensi = $monitoring->id;
                $this->tanggal = $monitoring->tanggal;
                $this->narasumber_id = $monitoring->narasumber_id;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;

                //set update true, berarti data akan diupdate
                $this->update = true;

                //ambil data kehadiran siswa yang sudah diinputkan
                $kehadiran = KehadiranKegnas::where('monitoring_kegna_id', $monitoring->id)->get()->all();
                foreach ($kehadiran as $k) {
                    $this->presensi[$k->siswa_id] = $k->status;
                }
            }
        } else {
            $this->hari = '';
            $this->waktu_mulai = '';
            $this->narasumber_id = '';
            $this->waktu_berakhir = '';
        }
    }

    //Rules validasi data inputan
    public function rules()
    {
        return [
            'tanggal' => 'required|date',
            'topik' => 'required',
            'narasumber_id' => 'required',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
        ];
    }

    //Custom Errror messages for validation
    protected $messages = [
        'tanggal.required' => 'Tanggal wajib diisi !',
        'narasumber_id.required' => 'Narasumber wajib diisi !',
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
        $this->narasumber_id = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //realtime validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function updatedFilterKelas()
    {
        //kosongkan data
        $this->empty();
        $angkatan_id = Kelas::find($this->filterKelas)->angkatan_id;
        $this->jadwal = JadwalKegiatan::where('kegiatan_id', $this->kegiatan->id)->where('angkatan_id', $angkatan_id)->first();
        if ($this->jadwal) {
            $this->hari = $this->jadwal->hari;
            $this->waktu_mulai = substr($this->jadwal->waktu_mulai, 0, -3);
            $this->waktu_berakhir = substr($this->jadwal->waktu_berakhir, 0, -3);
            $this->update = false;

            //Cek apakah jadwal sudah diinputkan
            if (MonitoringKegnas::where('jadwal_kegiatan_id', $this->jadwal->id)->where('tanggal', $this->tanggal)->first()) {
                //ambil data
                $monitoring = MonitoringKegnas::where('jadwal_kegiatan_id', $this->jadwal->id)->where('tanggal', $this->tanggal)->first();
                //set data berdasarkan data yang sudah diinputkan
                $this->editPresensi = $monitoring->id;
                $this->tanggal = $monitoring->tanggal;
                $this->narasumber_id = $monitoring->narasumber_id;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;

                //set update true, berarti data akan diupdate
                $this->update = true;

                //ambil data kehadiran siswa yang sudah diinputkan
                $kehadiran = KehadiranKegnas::where('monitoring_kegna_id', $monitoring->id)->get()->all();
                foreach ($kehadiran as $k) {
                    $this->presensi[$k->siswa_id] = $k->status;
                }
            }
        } else {
            $this->hari = '';
            $this->waktu_mulai = '';
            $this->narasumber_id = '';
            $this->waktu_berakhir = '';
        }

        //ambil data siswa kelas yang dipilih
        $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->get();
        // $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->orderBy('nama', 'asc')->all();

        //set presensi menjadi hadir bagi setiap siswa
        $this->presensi = [];
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }
    }

    public function save()
    {
        $this->validate();
        $monitoring = MonitoringKegnas::create([
            'tanggal' => $this->tanggal,
            'topik' => $this->topik,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'narasumber_id' => $this->narasumber_id,
            'jadwal_kegiatan_id' => $this->jadwal->id,
        ]);
        foreach ($this->presensi as $key => $value) {
            KehadiranKegnas::create([
                'siswa_id' => $key,
                'status' => $value,
                'monitoring_kegna_id' => $monitoring->id
            ]);
        }
        $this->update = true;
        session()->flash('message', 'Presensi berhasil diinputkan !');
        // $this->empty();
    }

    public function update()
    {
        $this->validate();
        MonitoringKegnas::where('id', $this->editPresensi)->update([
            'topik' => $this->topik,
            'narasumber_id' => $this->narasumber_id
        ]);
        foreach ($this->presensi as $key => $value) {
            KehadiranKegnas::where('monitoring_kegna_id', $this->editPresensi)->where('siswa_id', $key)->update([
                'status' => $value,
            ]);
        }
        session()->flash('message', 'Presensi berhasil diupdate !');
    }

    public function render()
    {
        return view('livewire.presensi-kegiatan-narasumber', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->paginate(10),
            'narasumber' => Narasumber::all()
        ]);
    }
}
