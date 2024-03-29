<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalGuruPiket;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use Illuminate\Support\Facades\Auth;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;

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

    public function mount()
    {
        //mengambil nama hari 
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        $this->filterKelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->first()->id;

        //mengambil tanggal
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');

        if (Auth::user()->role === 'guru') {
            $jadwalToday = JadwalGuruPiket::where('guru_id', Auth::user()->guru->id)->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where(function ($query) {
                $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('h:i'))->orWhere('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('h:i'));
            })->first();
            if ($jadwalToday === null) {
                $this->jadwal = [];
                $this->jadwalPengganti = [];
            } else {
                //Mengambil jadwal hari ini
                $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
                    [
                        'kelas' => function ($query) {
                            $query->select('id', 'nama');
                        },
                        'mataPelajaran' => function ($query) {
                            $query->select('id', 'nama');
                        },
                    ]
                )->with(['monitoringPembelajarans' => function ($query) {
                    $query->where('tanggal', $this->tanggal)->get();
                }])->get();

                //Get Jadwal Pengganti
                $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
            }
        } else {
            //Mengambil jadwal hari ini
            $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
                [
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                ]
            )->with(['monitoringPembelajarans' => function ($query) {
                $query->where('tanggal', $this->tanggal)->get();
            }])->get();

            //Get Jadwal Pengganti
            $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
        }
    }

    //Custom Errror messages for validation
    protected $messages = [
        'keterangan.required' => 'Keterangan wajib diisi !',
        'topik.required' => 'Topik wajib diisi !',
    ];

    public function updatedFilterKelas()
    {
        //Mengambil jadwal hari ini
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
            [
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]
        )->with(['monitoringPembelajarans' => function ($query) {
            $query->where('tanggal', $this->tanggal)->get();
        }])->get();

        //Get Jadwal Pengganti
        $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->get();
    }

    public function empty()
    {
        $this->editPresensi = null;
        $this->jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->where('hari', $this->day)->where('kelas_id', $this->filterKelas)->with(
            [
                'kelas' => function ($query) {
                    $query->select('id', 'nama');
                },
                'mataPelajaran' => function ($query) {
                    $query->select('id', 'nama');
                },
            ]
        )->with(['monitoringPembelajarans' => function ($query) {
            $query->where('tanggal', $this->tanggal)->get();
        }])->get();
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function showId($id)
    {
        //ambil data jadwal
        $data = JadwalPelajaran::find($id);

        //mengambil semua data siswa berdasarkan kelas default
        $this->student = Kelas::select('id')->where('id', $this->filterKelas)->first()->siswas;

        //set deafult presensi menjadi "hadir" untuk setiap siswa
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }

        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            // $this->tanggal = $monitoring->tanggal;
            $this->topik = $monitoring->topik;
            $this->keterangan = $monitoring->keterangan;
            $this->status = $monitoring->status_validasi;

            //ambil data kehadiran siswa yang sudah diinputkan
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
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

        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            // $this->tanggal = $monitoring->tanggal;
            $this->topik = $monitoring->topik;
            $this->editPresensi = $monitoring->id;
            $this->keterangan = $monitoring->keterangan;

            //ambil data kehadiran siswa yang sudah diinputkan
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
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
        //cek apakah ada data yang diinputkan
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first()) {
            //ambil data
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $id)->where('tanggal', $this->tanggal)->first();
            //set data berdasarkan data yang sudah diinputkan
            $this->editPresensi = $monitoring->id;
        }
        $this->dispatchBrowserEvent('show-valid-modal');
    }


    public function valid()
    {
        if (Auth::user()->role === 'guru') {
            $jadwalToday = JadwalGuruPiket::where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where(function ($query) {
                $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('h:i'))->orWhere('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('h:i'));
            })->first();
            if ($jadwalToday === null) {
                $guruPiketId = null;
            } else {
                $guruPiketId = $jadwalToday->guru_id;
            }
        } else {
            $guruPiketId = null;
        }
        MonitoringPembelajaran::where('id', $this->editPresensi)->update([
            'status_validasi' => 'valid',
            'keterangan' => '',
            'guru_piket_id' => $guruPiketId
        ]);

        session()->flash('message', 'Presensi berhasil diperbarui !');
        $this->empty();
        $this->dispatchBrowserEvent('close-valid-modal');
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
        MonitoringPembelajaran::where('id', $this->editPresensi)->update([
            'keterangan' => $this->keterangan,
            'topik' => $this->topik,
            'status_validasi' => 'tidak valid',
            'guru_piket_id' => $guruPiketId
        ]);
        foreach ($this->presensi as $key => $value) {
            KehadiranPembelajaran::where('monitoring_pembelajaran_id', $this->editPresensi)->where('siswa_id', $key)->update([
                'status' => $value,
            ]);
        }
        session()->flash('message', 'Presensi berhasil diperbarui !');
        $this->dispatchBrowserEvent('close-edit-modal');
        $this->empty();
        $this->render();
    }

    public function render()
    {
        return view('livewire.validasi-pembelajaran', [
            'jadwal' => $this->jadwal,
            'jadwalPengganti' => $this->jadwalPengganti,
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
        ]);
    }

    public function updatingFilterKelas()
    {
        $this->resetPage();
    }
}
