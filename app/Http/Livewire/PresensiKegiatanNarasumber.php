<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\JadwalGuruPiket;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use Illuminate\Support\Facades\Auth;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringKegiatanNarasumber;

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
    public $mapel, $mapelPengganti;
    //atribut inputan
    public $tanggal, $waktu_mulai, $waktu_berakhir, $topik;
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
        $jadwal = JadwalKegiatan::where('kegiatan_id', $kegiatan->id)->where('angkatan_id', $angkatan_id)->first();
        if ($jadwal) {
            $this->hari = $jadwal->hari;
            $this->waktu_mulai = $jadwal->waktu_mulai;
            $this->waktu_berakhir = $jadwal->waktu_berakhir;

            //Cek apakah jadwal sudah diinputkan
            if (MonitoringKegiatanNarasumber::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first()) {
                //ambil data
                $monitoring = MonitoringKegiatanNarasumber::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first();

                //set data berdasarkan data yang sudah diinputkan
                $this->editPresensi = $monitoring->id;
                $this->tanggal = $monitoring->tanggal;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;

                //set update true, berarti data akan diupdate
                $this->update = true;

                //ambil data kehadiran siswa yang sudah diinputkan
                $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
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
            $this->hari = '';
            $this->waktu_mulai = '';
            $this->waktu_berakhir = '';
        }
    }

    public function render()
    {
        return view('livewire.presensi-kegiatan-narasumber', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->orderBy('nama', 'asc')->paginate(10),
        ]);
    }
}
