<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
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


        //Ambil Jadwal Hari ini
    }
    public function render()
    {
        if ($this->filterTampilan == 'semua') {
            $this->tidakHadir = MonitoringPembelajaran::where('tanggal', $this->tanggal)->with(['jadwalPelajaran' => function ($query) {
                $query->with([
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                ]);
            }])->with(['kehadiranPembelajarans' => function ($query) {
                $query->where('status', '!=', 'hadir');
            }])->get();
            //Get Jadwal Pengganti
            $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->with(['jadwalPelajaran' => function ($query) {
                $query->with([
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                ])->with(['monitoringPembelajarans' => function ($query) {
                    $query->where('tanggal', $this->tanggal)->with(['kehadiranPembelajarans' => function ($query) {
                        $query->where('status', '!=', 'hadir');
                    }]);
                }]);
            }])->orderBy('waktu_mulai')->get();
        } else {
            $this->time =  \Carbon\Carbon::now()->translatedFormat('H:i');
            $this->tidakHadir = MonitoringPembelajaran::where('tanggal', $this->tanggal)->where('waktu_mulai', '<', $this->time)->where('waktu_berakhir', '>', $this->time)->with(['jadwalPelajaran' => function ($query) {
                $query->with([
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                ]);
            }])->with(['kehadiranPembelajarans' => function ($query) {
                $query->where('status', '!=', 'hadir');
            }])->get();
            //Get Jadwal Pengganti
            $this->jadwalPengganti = JadwalPengganti::where('tanggal', $this->tanggal)->with(['jadwalPelajaran' => function ($query) {
                $query->with([
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                ])->with(['monitoringPembelajarans' => function ($query) {
                    $query->where('tanggal', $this->tanggal)->where('waktu_mulai', '<', $this->time)->where('waktu_berakhir', '>', $this->time)->with(['kehadiranPembelajarans' => function ($query) {
                        $query->where('status', '!=', 'hadir');
                    }]);
                }]);
            }])->orderBy('waktu_mulai')->get();
        }

        if ($this->filterTampilan == 'semua') {
            return view('livewire.pembelajaran-monitoring', [
                'jadwal' => JadwalPelajaran::select('id', 'waktu_mulai', 'hari', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->with(
                    [
                        'kelas' => function ($query) {
                            $query->select('id', 'nama');
                        },
                        'mataPelajaran' => function ($query) {
                            $query->select('id', 'nama');
                        },
                        'guru' => function ($query) {
                            $query->select('id', 'nama');
                        },
                    ]
                )->with(['monitoringPembelajarans' => function ($query) {
                    $query->where('tanggal', $this->tanggal)->get();
                }])->orderBy('waktu_mulai')->paginate(12)
            ]);
        } else {
            return view('livewire.pembelajaran-monitoring', [
                'jadwal' => JadwalPelajaran::select('id', 'waktu_mulai', 'hari', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id', 'guru_id')->where('hari', $this->day)->where('waktu_mulai', '<', $this->time)->where('waktu_berakhir', '>', $this->time)->with(
                    [
                        'kelas' => function ($query) {
                            $query->select('id', 'nama');
                        },
                        'mataPelajaran' => function ($query) {
                            $query->select('id', 'nama');
                        },
                        'guru' => function ($query) {
                            $query->select('id', 'nama');
                        },
                    ]
                )->with(['monitoringPembelajarans' => function ($query) {
                    $query->where('tanggal', $this->tanggal)->get();
                }])->orderBy('waktu_mulai')->paginate(12)
            ]);
        }
    }
}
